<?php

namespace App\Jobs;

use App\ServerApp;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Imtigger\LaravelJobStatus\Trackable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AppWpSearchReplaceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Trackable;

    private $app;
    private $search;
    private $replace;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ServerApp $app, Request $request)
    {
        $this->prepareStatus();
        $this->app = $app;

        $this->search = $request->input('search');
        $this->replace = $request->input('replace');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call('app:wp:search-replace', [
            'search' => $this->search,
            'replace' => $this->replace,
            '--app' => $this->app->name,
            '--job-status-id' => $this->getJobStatusId(),
            '--disable-tty' => true
        ]);
    }
}
