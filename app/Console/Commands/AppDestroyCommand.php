<?php

namespace App\Console\Commands;

use App\Console\Command;

class AppDestroyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:destroy 
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
    protected $description = 'Destroy a single app.';

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

        $this->appModel->setStateDestroying();

        $vars = [
            "host" => $this->host,
            "user" => $this->user,
            "app" => $this->app,
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name',
            'user' => 'required|exists:server_users,name',
            'app' => 'required|exists:server_apps,name'
        ];

        $this->runPlaybook($this->appModel, 'app/destroy.yml', $vars, $validations, "Failed to destroy app.");

        $this->appModel->delete();
    }
}
