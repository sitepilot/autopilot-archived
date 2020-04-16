<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Traits\HasState;

class AppCertRequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cert:request
        {--app= : The app name (optional)}
        {--skip-tags= : Comma separated list of skipped tags (optional)}
        {--nova-batch-id= : The nova batch id (optional)}
        {--disable-tty : Disable TTY}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Request SSL certificate for an app.';

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
            "domain" => $this->appModel->getVar('domain'),
            "aliases" => $this->appModel->getVar('aliases')
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name,state,' . HasState::getProvisionedIndex(),
            'user' => 'required|exists:server_users,name,state,' . HasState::getProvisionedIndex(),
            'app' => 'required|exists:server_apps,name,state,' . HasState::getProvisionedIndex(),
            'domain' => 'required|min:3',
            'aliases' => 'array',
        ];

        $this->runPlaybook($this->appModel, 'app/cert.yml', $vars, $validations, "Failed to provision app certificate.");

        $this->appModel->setVar('ssl', true, true)->save();
    }
}
