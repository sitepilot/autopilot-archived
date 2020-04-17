<?php

namespace App\Jobs;

use App\ServerHost;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ServerTestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $host;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ServerHost $host)
    {
        $this->host = $host;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call('server:test', [
            '--host' => $this->host->name
        ]);
    }
}
