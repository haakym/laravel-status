<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class StatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for available updates';
    
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
        $updatesInfo = $this->checkForUpdates();
        $this->table($updatesInfo['headers'], $updatesInfo['values']);
        $this->info($updatesInfo['update']);

    }

    private function getAppVersion()
    {
        return $this->laravel->version();
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

        $jsonResponse = file_get_contents('https://api.github.com/repos/laravel/laravel/tags', false, $context); // e.g. v5.4.15

        $response = json_decode($jsonResponse, true);

        // TODO: check if response is empty

        $latestVersion = $response[0]['name'];

        $version = substr($latestVersion, 1, strlen($latestVersion)); // 5.4.15

        return $version;
    }

    private function checkForUpdates()
    {
        $semVer = ['Major', 'Minor', 'Patch'];

        $latestVersion = explode('.', $this->getLatestLaravelVersion());

        $currentVersion = explode('.', '5.3.0');
        
        // $currentVersion = explode('.', $this->getAppVersion());

        $update = 'You are up to date with the latest version of Laravel!';

        for ($i = 0; $i < count($semVer); $i++) {            

            if ($latestVersion[$i] > $currentVersion[$i]) {
                $update = $semVer[$i] . ' update available, run <bg=yellow;fg=blue>composer update laravel/laravel</>';
                // $currentVersion[$i] = "<bg=red>{$currentVersion[$i]}</>";
                // $latestVersion[$i] = "<bg=green>{$latestVersion[$i]}</>";
            }
        }

        $headers = array_merge(['Version'], $semVer);

        $values = [
            [
                'Version' => 'Latest',
                'Major'   => $latestVersion[0],
                'Minor'   => $latestVersion[1],
                'Patch'   => $latestVersion[2],
            ],
            [
                'Version' => 'App',
                'Major'   => $currentVersion[0],
                'Minor'   => $currentVersion[1],
                'Patch'   => $currentVersion[2],
            ],
        ];

        $output = compact('headers', 'values', 'update');

        return $output;

    }
}
