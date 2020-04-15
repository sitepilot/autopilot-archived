<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Traits\HasState;

class AppWpInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:wp:install 
        {--app= : The app name (optional)}
        {--nova-batch-id= : The nova batch id (optional)}
        {--disable-tty : Disable TTY}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install WordPress for a single app.';

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
        $this->askApp();

        $vars = [
            "host" => $this->host,
            "user" => $this->user,
            "app" => $this->app,
            "url" => 'https://' . $this->appModel->getVar('domain'),
            "title" => ucfirst($this->appModel->getVar('name')),
            "admin_user" => $this->appModel->getVar('admin_user', 'wordpress'),
            "admin_pass" => $this->appModel->getVar('admin_pass', 'wordpress'),
            "admin_email" => $this->appModel->getVar('admin_email', 'wordpress'),
            "db_name" => $this->appModel->getVar('db_name', 'wordpress'),
            'db_user' => $this->appModel->getVar('db_user', 'wordpress', $this->appModel->user->getVar('name')),
            'db_pass' => $this->appModel->getVar('db_pass', 'wordpress', $this->appModel->user->getVar('mysql_password')),
            'db_host' => $this->appModel->getVar('db_host', 'wordpress', '127.0.0.1'),
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name,state,' . HasState::getProvisionedIndex(),
            'user' => 'required|exists:server_users,name,state,' . HasState::getProvisionedIndex(),
            'app' => 'required|exists:server_apps,name,state,' . HasState::getProvisionedIndex(),
            'db_name' => 'required|exists:server_databases,name,state,' . HasState::getProvisionedIndex(),
            'db_user' => 'required|exists:server_users,name,state,' . HasState::getProvisionedIndex(),
            'url' => 'required|url',
            'title' => 'required',
            'admin_user' => 'required',
            'admin_pass' => 'required',
            'admin_email' => 'required|email',
            'db_pass' => 'required',
            'db_host' => 'required'
        ];

        $this->runAppPlaybook('wordpress/install.yml', $vars, $validations, "Failed to install WordPress.");
    }
}
