<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Manager;

class GetMissingKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:getmissingkeys';

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
    public function handle(Manager $manager)
    {
        $counter = $this->manager->findTranslations(null);
        $this->info('Done importing, processed '.$counter.' items!');
    }
}
