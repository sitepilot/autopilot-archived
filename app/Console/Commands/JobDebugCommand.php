<?php

namespace App\Console\Commands;

use App\User;
use App\Console\Command;
use Imtigger\LaravelJobStatus\JobStatus;

class JobDebugCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump job output.';

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
        $jobs = JobStatus::orderBy('id', 'desc')->limit(5)->get();

        foreach($jobs as $job) {
            $this->line("");
            $this->line("========= $job->type =========");
            $this->line("");
            $this->line($job->output);
            $this->line("========= End $job->type =========");
        }
    }
}
