<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Status extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get current status of application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*
            - env
                - find laravel commands and use existing env
            - route cache
                - current
                - is routes different to cached
            - settings cache
            - up or down
        */

        $this->info('Display this on the screen');
        $this->info('Display this on the screen');
    }
}
