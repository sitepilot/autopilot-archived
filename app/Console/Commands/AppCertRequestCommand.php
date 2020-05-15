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
        $app = $this->askApp();

        $vars = [
            "host" => $app->host->name,
            "user" => $app->user->name,
            "app" => $app->name,
            "domain" => $app->getVar('domain'),
            "aliases" => $app->getVar('aliases'),
            "email" => $app->host->group->getVar('cert_email')
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name,state,' . HasState::getProvisionedIndex(),
            'user' => 'required|exists:server_users,name,state,' . HasState::getProvisionedIndex(),
            'app' => 'required|exists:server_apps,name',
            'domain' => 'required|min:3',
            'aliases' => 'array',
            'email' => 'required|email'
        ];

        $app->setStateRequestingCert();

        $this->runPlaybook($app, 'app/cert-request.yml', $vars, $validations, "Failed to provision app certificate for $app->name.", true);

        $app->setVar('ssl', true)->save();
    }
}
