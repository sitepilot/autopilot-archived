<?php

namespace App\Jobs;

use App\ServerApp;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AppWpCheckStateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $app;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ServerApp $app)
    {
        $this->app = $app;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call('app:wp:check-state', [
            '--app' => $this->app->name
        ]);
    }
}
