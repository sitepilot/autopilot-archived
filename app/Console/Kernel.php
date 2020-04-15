<?php

namespace App\Console;

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
            $schedule->command("server:cert:renew --host=$host->name --disable-tty")->dailyAt('08:05');
        }

        // Test provisioned hosts
        $hosts = ServerHost::where('state', ServerHost::getProvisionedIndex())->get();
        foreach ($hosts as $host) {
            $schedule->command("server:test --host=$host->name --disable-tty")->hourly();
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
