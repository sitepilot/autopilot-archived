<?php

namespace App\Console\Commands;

use App\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Imtigger\LaravelJobStatus\JobStatus;

class UserDestroyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:destroy 
        {--user= : The user name (optional)}
        {--tags= : Comma separated list of tags (optional)}
        {--skip-tags= : Comma separated list of skipped tags (optional)}
        {--nova-batch-id= : The nova batch id (optional)}
        {--job-status-id= : The job status id (optional)}
        {--disable-tty : Disable TTY}
        {--debug : Show debug info}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Destroy a single user.';

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
        $user = $this->askUser();

        $user->setStateDestroying();

        foreach ($user->apps as $app) {
            Artisan::call('app:destroy', [
                '--app' => $app->name
            ]);
        }

        foreach ($user->databases as $database) {
            Artisan::call('database:destroy', [
                '--database' => $database->name
            ]);
        }

        $vars = [
            "host" => $user->host->name,
            "user" => $user->name,
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name',
            'user' => 'required|exists:server_users,name',
        ];

        $this->runPlaybook($user, 'user/destroy.yml', $vars, $validations, "Failed to destroy user: $user->name.");

        $user->delete();
        
        $this->jobFinished();
    }
}
