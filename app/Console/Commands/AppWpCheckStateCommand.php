<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Traits\HasState;

class AppWpCheckStateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:wp:check-state
        {--app= : The app name (optional)}
        {--tags= : Comma separated list of tags (optional)}
        {--skip-tags= : Comma separated list of skipped tags (optional)}
        {--nova-batch-id= : The nova batch id (optional)}
        {--disable-tty=true : Disable TTY}
        {--debug : Show debug info}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for WordPress updates.';

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
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name,state,' . HasState::getProvisionedIndex(),
            'user' => 'required|exists:server_users,name,state,' . HasState::getProvisionedIndex(),
            'app' => 'required|exists:server_apps,name,state,' . HasState::getProvisionedIndex(),
        ];

        $result = $this->runPlaybook($this->appModel, 'wordpress/check-state.yml', $vars, $validations, "Failed to check WordPress state.", false);

        if ('yes' == $this->findBetween($result->getProcessBuffer(), '[autopilot-has-update]', '[/autopilot-has-update]')) {
            $this->appModel->setVar('wordpress.state.has_update', true)->save();
        } else {
            $this->appModel->setVar('wordpress.state.has_update', false)->save();
        }

        if ($version = $this->findBetween($result->getProcessBuffer(), '[autopilot-core-version]', '[/autopilot-core-version]')) {
            $this->appModel->setVar('wordpress.state.core_version', $version)->save();
        }

        if ($plugins = $this->findBetween($result->getProcessBuffer(), '[autopilot-plugins]', '[/autopilot-plugins]')) {
            $plugins = json_decode($plugins);
            if (is_array($plugins)) {
                $savePlugins = [];
                foreach ($plugins as $plugin) {
                    $plugin = [
                        'name' => $plugin->name,
                        'status' => $plugin->status,
                        'update' => $plugin->update,
                        'version' => $plugin->version
                    ];
                    $savePlugins[] = $plugin;
                }
                $this->appModel->setVar('wordpress.state.plugins', $savePlugins)->save();
            }
        }

        if ($themes = $this->findBetween($result->getProcessBuffer(), '[autopilot-themes]', '[/autopilot-themes]')) {
            $themes = json_decode($themes);
            if (is_array($themes)) {
                $saveThemes = [];
                foreach ($themes as $theme) {
                    $theme = [
                        'name' => $theme->name,
                        'status' => $theme->status,
                        'update' => $theme->update,
                        'version' => $theme->version
                    ];
                    $saveThemes[] = $theme;
                }
                $this->appModel->setVar('wordpress.state.themes', $saveThemes)->save();
            }
        }
    }
}
