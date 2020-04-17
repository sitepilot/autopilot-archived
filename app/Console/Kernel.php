<?php

namespace App\Console;

use App\ServerApp;
use App\ServerHost;
use App\Jobs\ServerTestJob;
use App\Jobs\AppWpCheckStateJob;
use App\Jobs\ServerCertRenewJob;
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
        // Schedule host jobs
        $hosts = ServerHost::where('state', ServerHost::getProvisionedIndex())->get();
        foreach ($hosts as $host) {
            $schedule->job(new ServerCertRenewJob($host))->dailyAt('08:05');
            $schedule->job(new ServerTestJob($host))->dailyAt('10:05');
        }

        // Schedule app jobs
        $apps = ServerApp::where('state', ServerApp::getProvisionedIndex())->get();
        foreach ($apps as $app) {
            if ($app->getVar('wordpress')) {
                $schedule->job(new AppWpCheckStateJob($app))->twiceDaily('6', '12');
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
