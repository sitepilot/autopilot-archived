<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Traits\HasState;

class AppProvisionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:provision 
        {--app= : The app name (optional)}
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
    protected $description = 'Provision a single app.';

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
            "domain" => $app->getVar('domain'),
            "aliases" => $app->getVar('aliases'),
            'php_version' => $app->getVar('php.version', '74')
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name,state,' . HasState::getProvisionedIndex(),
            'user' => 'required|exists:server_users,name,state,' . HasState::getProvisionedIndex(),
            'app' => 'required|exists:server_apps,name',
            'domain' => 'required|min:3',
            'aliases' => 'array',
            'php_version' => 'in:74,73'
        ];

        $app->setStateProvisioning();

        $this->runPlaybook($app, 'app/provision.yml', $vars, $validations, "Failed to provision app: $app->name.");
    }
}
