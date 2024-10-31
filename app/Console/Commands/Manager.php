<?php

namespace App\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Spatie\TranslationLoader\LanguageLine;

class Manager
{
    const JSON_GROUP = '_json';

    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;
    /** @var \Illuminate\Filesystem\Filesystem */
    protected $files;
    /** @var \Illuminate\Contracts\Events\Dispatcher */
    protected $events;

    protected $config;

    protected $locales;

    protected $ignoreLocales;

    protected $ignoreFilePath;

    public function __construct(Application $app, Filesystem $files, Dispatcher $events)
    {
        $this->app = $app;
        $this->files = $files;
        $this->events = $events;
        $this->config = $app['config']['translation-loader'];
        $this->ignoreFilePath = storage_path('.ignore_locales');
        $this->locales = [];
    }

    public function importTranslations($replace = false, $base = null, $import_group = false)
    {
        $counter = 0;
        //allows for vendor lang files to be properly recorded through recursion.
        $vendor = true;
        if ($base == null) {
            $base = $this->app['path.lang'];
            $vendor = false;
        }

        foreach ($this->files->directories($base) as $langPath) {
            $locale = basename($langPath);
            if ($locale == 'en'){
                $vendorName = $this->files->name($this->files->dirname($langPath));
                foreach ($this->files->allfiles($langPath) as $file) {
                    $info = pathinfo($file);
                    $group = $info['filename'];
                    if ($import_group) {
                        if ($import_group !== $group) {
                            continue;
                        }
                    }

                    if (in_array($group, $this->config['exclude_groups'])) {
                        continue;
                    }
                    $subLangPath = str_replace($langPath.DIRECTORY_SEPARATOR, '', $info['dirname']);
                    $subLangPath = str_replace(DIRECTORY_SEPARATOR, '/', $subLangPath);
                    $langPath = str_replace(DIRECTORY_SEPARATOR, '/', $langPath);

                    if ($subLangPath != $langPath) {
                        $group = $subLangPath.'/'.$group;
                    }

                    if (! $vendor) {
                        $translations = \Lang::getLoader()->load('en', $group);
                    } else {
                        $translations = include $file;
                        $group = 'vendor/'.$vendorName;
                    }

                    if ($translations && is_array($translations)) {
                        foreach (Arr::dot($translations) as $key => $value) {
                            $importedTranslation = $this->importTranslation($key, $value, $locale, $group, $replace);
                            $counter += $importedTranslation ? 1 : 0;
                        }
                    }
                }
            }
        }

        return $counter;
    }

    public function importTranslation($key, $value, $locale, $group, $replace = false)
    {

        // process only string values
        if (is_array($value)) {
            return false;
        }
        $value = (string) $value;
        $zh_TW = \Lang::getLoader()->load('zh-TW', $group);
        if (array_key_exists($key, $zh_TW)){
            $translation = Languageline::create([
                'group'  => $group,
                'key'    => $key,
                'text'   => ['en' => $value, 'zh-TW' => $zh_TW[$key]],
            ]);
        }
        else{
            if(str_contains($key,'.')){
                $complexkey= explode('.', $key);
                if (array_key_exists($complexkey[0], $zh_TW) && $group != 'validation'){
                    $translation = Languageline::create([
                        'group'  => $group,
                        'key'    => $key,
                        'text'   => ['en' => $value, 'zh-TW' => $zh_TW[$complexkey[0]][$complexkey[1]]],
                    ]);
                }
                else{
                    $this->keyHasEnglishTranslationOnly($group, $key, $value);
                }
            }
            else{
                $this->keyHasEnglishTranslationOnly($group, $key, $value);
            }
        }

        return true;
    }

    public function findTranslations($path = null)
    {
        $path = $path ?: base_path();
        $groupKeys = [];
        $stringKeys = [];
        $functions = $this->config['trans_functions'];

        $groupPattern =                          // See https://regex101.com/r/WEJqdL/6
            "[^\w|>]".                          // Must not have an alphanum or _ or > before real method
            '('.implode('|', $functions).')'.  // Must start with one of the functions
            "\(".                               // Match opening parenthesis
            "[\'\"]".                           // Match " or '
            '('.                                // Start a new group to match:
            '[a-zA-Z0-9_-]+'.               // Must start with group
            "([.](?! )[^\1)]+)+".             // Be followed by one or more items/keys
            ')'.                                // Close group
            "[\'\"]".                           // Closing quote
            "[\),]";                            // Close parentheses or new parameter

        $stringPattern =
            "[^\w]".                                     // Must not have an alphanum before real method
            '('.implode('|', $functions).')'.             // Must start with one of the functions
            "\(\s*".                                       // Match opening parenthesis
            "(?P<quote>['\"])".                            // Match " or ' and store in {quote}
            "(?P<string>(?:\\\k{quote}|(?!\k{quote}).)*)". // Match any string that can be {quote} escaped
            "\k{quote}".                                   // Match " or ' previously matched
            "\s*[\),]";                                    // Close parentheses or new parameter

        // Find all PHP + Twig files in the app folder, except for storage
        $finder = new Finder();
        $finder->in($path)->exclude('storage')->exclude('vendor')->name('*.php')->name('*.twig')->name('*.vue')->files();

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            // Search the current file for the pattern
            if (preg_match_all("/$groupPattern/siU", $file->getContents(), $matches)) {
                // Get all matches
                foreach ($matches[2] as $key) {
                    $groupKeys[] = $key;
                }
            }

            if (preg_match_all("/$stringPattern/siU", $file->getContents(), $matches)) {
                foreach ($matches['string'] as $key) {
                    if (preg_match("/(^[a-zA-Z0-9_-]+([.][^\1)\ ]+)+$)/siU", $key, $groupMatches)) {
                        // group{.group}.key format, already in $groupKeys but also matched here
                        // do nothing, it has to be treated as a group
                        continue;
                    }

                    //TODO: This can probably be done in the regex, but I couldn't do it.
                    //skip keys which contain namespacing characters, unless they also contain a
                    //space, which makes it JSON.
                    if (! (Str::contains($key, '::') && Str::contains($key, '.'))
                         || Str::contains($key, ' ')) {
                        $stringKeys[] = $key;
                    }
                }
            }
        }
        // Remove duplicates
        $groupKeys = array_unique($groupKeys);
        $stringKeys = array_unique($stringKeys);

        // Add the translations to the database, if not existing.
        foreach ($groupKeys as $key) {
            // Split the group and item
            list($group, $item) = explode('.', $key, 2);
            $this->missingKey('', $group, $item);
        }

        foreach ($stringKeys as $key) {
            $group = self::JSON_GROUP;
            $item = $key;
            $this->missingKey('', $group, $item);
        }

        // Return the number of found translations
        return count($groupKeys + $stringKeys);
    }

    public function missingKey($namespace, $group, $key)
    {
        if (! in_array($group, $this->config['exclude_groups'])) {
            Languageline::create([
                'group'  => $group,
                'key'    => $key,
                'text'   => ['en' => '', 'zh-TW' => ''],
            ]);
        }
    }
    public function keyHasEnglishTranslationOnly($group, $key, $value){
        $translation = Languageline::create([
            'group'  => $group,
            'key'    => $key,
            'text'   => ['en' => $value, 'zh-TW' => ''],
        ]);
    }

}