<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

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
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $headers = ['name', 'value'];

        $values = [
            [
                'name' => 'Version',
                'value' => $this->getAppVersion()
            ],
            [
                'name' => 'Environment',
                'value' => $this->getEnvironment()
            ],
            [
                'name' => 'Routes Cached?',
                'value' => $this->routesAreCached() ? 'Yes' : 'No'
            ],
            [
                'name' => 'Config Cached?',
                'value' => $this->configIsCached() ? 'Yes' : 'No'
            ],
            [
                'name' => 'Maintenance mode?',
                'value' => $this->isInMaintenanceMode() ? 'Yes' : 'No'
            ],
            [
                'name' => 'Latest version',
                'value' => $this->getLatestLaravelVersion()
            ],
        ];

        $this->table($headers, $values);
    }

    private function getAppVersion()
    {
        return $this->laravel->version();
    }

    private function getEnvironment()
    {
        return $this->laravel['env'];
    }

    private function routesAreCached()
    {
        return $this->files->exists($this->laravel->getCachedRoutesPath());
    }

    private function configIsCached()
    {
        return $this->files->exists($this->laravel->getCachedConfigPath());
    }

    private function isInMaintenanceMode()
    {
        return $this->files->exists($this->laravel->storagePath().'/framework/down');
    }

    private function getLatestLaravelVersion()
    {
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: PHP'
                ]
            ]
        ];

        $context = stream_context_create($options);

        $jsonResponse = file_get_contents('https://api.github.com/repos/laravel/laravel/tags', false, $context); // v5.4.15

        $stringVersion = json_decode($jsonResponse, true)[0]['name'];

        $version = substr($stringVersion, 1, strlen($stringVersion)); // 5.4.15

        return $version;
    }

    private function checkForUpdates()
    {
        $latestVersion = explode('.', $version);

        $currentVersion = $this->getAppVersion();
    }
}
