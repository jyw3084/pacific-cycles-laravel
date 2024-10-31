<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Manager;

class ImportKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:importkeys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets translation keys in App';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    /** @var \App\Console\Commands\Manager */
    protected $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $replace = false;
        $counter = $this->manager->importTranslations($replace);
        $this->info('Done importing, processed '.$counter.' items!');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
}
