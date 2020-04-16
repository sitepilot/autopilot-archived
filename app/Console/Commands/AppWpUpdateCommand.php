<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Traits\HasState;

class AppWpUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:wp:update 
        {--app= : The app name (optional)}
        {--skip-tags= : Comma separated list of skipped tags (optional)}
        {--nova-batch-id= : The nova batch id (optional)}
        {--disable-tty : Disable TTY}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update WordPress for a single app.';

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
            "update_core" => $this->appModel->getVar('update_core', 'wordpress'),
            "update_plugins" => $this->appModel->getVar('update_plugins', 'wordpress'),
            "update_themes" => $this->appModel->getVar('update_themes', 'wordpress'),
            "update_exclude" => $this->appModel->getVar('update_exclude', 'wordpress', [])
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name,state,' . HasState::getProvisionedIndex(),
            'user' => 'required|exists:server_users,name,state,' . HasState::getProvisionedIndex(),
            'app' => 'required|exists:server_apps,name,state,' . HasState::getProvisionedIndex(),
            'update_core' => 'required|boolean',
            'update_plugins' => 'required|boolean',
            'update_themes' => 'required|boolean',
            'update_exclude' => 'array',
        ];

        $this->runPlaybook($this->appModel, 'wordpress/update.yml', $vars, $validations, "Failed to update WordPress.");
    }
}
