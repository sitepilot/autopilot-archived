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
        {--job-status-id= : The job status id (optional)}
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
        $app = $this->askApp();

        $vars = [
            "host" => $app->host->name,
            "user" => $app->user->name,
            "app" => $app->name,
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name,state,' . HasState::getProvisionedIndex(),
            'user' => 'required|exists:server_users,name,state,' . HasState::getProvisionedIndex(),
            'app' => 'required|exists:server_apps,name',
        ];

        $app->setStateCheckingWp();

        $result = $this->runPlaybook($app, 'wordpress/check-state.yml', $vars, $validations, "Failed to check WordPress state for app: $app->name.");

        if ('yes' == $this->findBetween($result, '[autopilot-has-update]', '[/autopilot-has-update]')) {
            $app->setVar('wordpress.state.has_update', true)->save();
        } else {
            $app->setVar('wordpress.state.has_update', false)->save();
        }

        if ($version = $this->findBetween($result, '[autopilot-core-version]', '[/autopilot-core-version]')) {
            $app->setVar('wordpress.state.core_version', $version)->save();
        }

        if ($plugins = $this->findBetween($result, '[autopilot-plugins]', '[/autopilot-plugins]')) {
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
                $app->setVar('wordpress.state.plugins', $savePlugins)->save();
            }
        }

        if ($themes = $this->findBetween($result, '[autopilot-themes]', '[/autopilot-themes]')) {
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
                $app->setVar('wordpress.state.themes', $saveThemes)->save();
            }
        }
    }
}
