<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Traits\HasState;

class AppWpLoginCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:wp:login
        {login?}
        {--app= : The app name (optional)}
        {--tags= : Comma separated list of tags (optional)}
        {--skip-tags= : Comma separated list of skipped tags (optional)}
        {--nova-batch-id= : The nova batch id (optional)}
        {--disable-tty : Disable TTY}
        {--debug : Show debug info}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Login to WordPress.';

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
        $app = $this->askApp();

        $vars = [
            "host" => $app->host->name,
            "user" => $app->user->name,
            "app" => $app->name,
            "login" => $this->argument('login') ? $this->argument('login') : $app->getVar('wordpress.admin_user'),
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name,state,' . HasState::getProvisionedIndex(),
            'user' => 'required|exists:server_users,name,state,' . HasState::getProvisionedIndex(),
            'app' => 'required|exists:server_apps,name,state,' . HasState::getProvisionedIndex(),
            'login' => 'required|min:3'
        ];

        $this->runPlaybook($app, 'wordpress/login.yml', $vars, $validations, "Failed to login to WordPress for app: $app->name.", false);
    }
}
