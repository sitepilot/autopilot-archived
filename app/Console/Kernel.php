<?php

namespace App\Console;

use App\ServerApp;
use App\ServerHost;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Renew SSL certificates on provisioned hosts
        $hosts = ServerHost::where('state', ServerHost::getProvisionedIndex())->get();
        foreach ($hosts as $host) {
            $schedule->command("server:cert:renew --host=$host->name")->dailyAt('08:05');
        }

        // Test provisioned hosts
        $hosts = ServerHost::where('state', ServerHost::getProvisionedIndex())->get();
        foreach ($hosts as $host) {
            $schedule->command("server:test --host=$host->name")->hourly();
        }

        // Check WordPress state
        $apps = ServerApp::where('state', ServerApp::getProvisionedIndex())->get();
        foreach ($apps as $app) {
            if ($app->getVar('wordpress')) {
                $schedule->command("app:wp:check-state --app=$app->name")->dailyAt('10:17');
            }
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
