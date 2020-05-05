<?php

namespace App\Jobs;

use App\ServerApp;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Imtigger\LaravelJobStatus\Trackable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AppWpUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    private $app;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ServerApp $app)
    {
        $this->prepareStatus();
        $this->app = $app;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call('app:wp:update', [
            '--app' => $this->app->name,
            '--job-status-id' => $this->getJobStatusId(),
            '--disable-tty' => true
        ]);
    }
}
