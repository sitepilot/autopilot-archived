<?php

namespace App\Console\Commands;

use App\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UserDestroyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:destroy 
        {--user= : The user name (optional)}
        {--skip-tags= : Comma separated list of skipped tags (optional)}
        {--nova-batch-id= : The nova batch id (optional)}
        {--disable-tty : Disable TTY}';

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
        $this->askUser();

        $this->userModel->setStateDestroying();

        foreach ($this->userModel->apps as $app) {
            Artisan::call('app:destroy', [
                '--app' => $app->name
            ]);
        }

        foreach ($this->userModel->databases as $database) {
            Artisan::call('database:destroy', [
                '--database' => $database->name
            ]);
        }

        $vars = [
            "host" => $this->host,
            "user" => $this->user,
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name',
            'user' => 'required|exists:server_users,name',
        ];

        $this->runPlaybook($this->userModel, 'user/destroy.yml', $vars, $validations, "Failed to destroy user.");

        $this->userModel->delete();
    }
}
