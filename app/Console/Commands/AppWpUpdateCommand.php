<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Traits\HasState;
use Illuminate\Support\Facades\Artisan;

class AppWpUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:wp:update 
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
        $app = $this->askApp();

        $vars = [
            "host" => $app->host->name,
            "user" => $app->user->name,
            "app" => $app->name,
            "update_core" => $app->getVar('wordpress.update_core'),
            "update_plugins" => $app->getVar('wordpress.update_plugins'),
            "update_themes" => $app->getVar('wordpress.update_themes'),
            "update_exclude" => $app->getVar('wordpress.update_exclude', [])
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

        $this->runPlaybook($app, 'wordpress/update.yml', $vars, $validations, "Failed to update WordPress.", false);

        Artisan::call('app:wp:check-state', [
            '--app' => $app->name,
            '--nova-batch-id' => $this->option('nova-batch-id')
        ]);
    }
}
