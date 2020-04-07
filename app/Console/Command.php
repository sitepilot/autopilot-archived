<?php

namespace App\Console;

use App\ServerApp;
use App\ServerHost;
use App\ServerUser;
use App\ServerDatabase;
use Illuminate\Console\Command as ConsoleCommand;

class Command extends ConsoleCommand
{
    protected $host = null;
    protected $user = null;
    protected $app = null;
    protected $database = null;
    protected $hostModel = null;
    protected $userModel = null;
    protected $appModel = null;
    protected $databaseModel = null;
    protected static $buffer = null;

    /**
     * Ask for a host.
     *
     * @return void
     */
    public function askHost()
    {
        if ($this->host = $this->option('host')) {
            $this->hostModel = ServerHost::where('name', $this->host)->first();

            return;
        }

        if (!$this->host) {
            $hosts = ServerHost::get();
            $options = [];

            foreach ($hosts as $host) {
                $options[] = $host->name;
            }

            if (count($options) > 0) {
                $this->host = $this->choice('Select a host', $options);
                $this->hostModel = ServerHost::where('name', $this->host)->first();
            }
        }
    }

    /**
     * Ask for a user.
     *
     * @return void
     */
    public function askUser()
    {
        if ($this->user = $this->option('user')) {
            $user = ServerUser::where('name', $this->option('user'))->first();
            $this->userModel = ServerUser::where('name', $this->user)->first();

            if (isset($user->host->name)) {
                $this->host = $user->host->name;
                $this->hostModel = ServerHost::where('name', $this->host)->first();
            }

            return;
        }

        if (!$this->user) {
            $users = ServerUser::get();
            $options = [];
            $hosts = [];

            foreach ($users as $user) {
                $options[] = $user->name;
                $hosts[$user->name] = $user->host->name;
            }

            if (count($options) > 0) {
                $this->user = $this->choice('Select a user', $options);
                $this->userModel = ServerUser::where('name', $this->user)->first();
                $this->host = $hosts[$this->user];
                $this->hostModel = ServerHost::where('name', $this->host)->first();
            }
        }
    }

    /**
     * Ask for a app.
     *
     * @return void
     */
    public function askApp()
    {
        if ($this->app = $this->option('app')) {
            $app = ServerApp::where('name', $this->option('app'))->first();
            $this->appModel = ServerApp::where('name', $this->app)->first();

            if (isset($app->user->name)) {
                $this->user = $app->user->name;
                $this->userModel = ServerUser::where('name', $this->user)->first();
            }

            if (isset($app->user->host->name)) {
                $this->host = $app->user->host->name;
                $this->hostModel = ServerHost::where('name', $this->host)->first();
            }

            return;
        }

        if (!$this->app) {
            $apps = ServerApp::get();
            $options = [];
            $hosts = [];
            $users = [];

            foreach ($apps as $app) {
                $options[] = $app->name;
                $users[$app->name] = $app->user->name;
                $hosts[$app->name] = $app->user->host->name;
            }

            if (count($options) > 0) {
                $this->app = $this->choice('Select an app', $options);
                $this->appModel = ServerApp::where('name', $this->app)->first();
                $this->user = $users[$this->app];
                $this->userModel = ServerUser::where('name', $this->user)->first();
                $this->host = $hosts[$this->app];
                $this->hostModel = ServerHost::where('name', $this->host)->first();
            }
        }
    }

    /**
     * Ask for a database.
     *
     * @return void
     */
    public function askDatabase()
    {
        if ($this->database = $this->option('database')) {
            $database = ServerDatabase::where('name', $this->option('database'))->first();
            $this->databaseModel = ServerDatabase::where('name', $this->database)->first();

            if (isset($database->user->name)) {
                $this->user = $database->user->name;
                $this->userModel = ServerUser::where('name', $this->user)->first();
            }

            if (isset($database->user->host->name)) {
                $this->host = $database->user->host->name;
                $this->hostModel = ServerHost::where('name', $this->host)->first();
            }

            return;
        }

        if (!$this->database) {
            $databases = ServerDatabase::get();
            $options = [];
            $hosts = [];
            $users = [];

            foreach ($databases as $database) {
                $options[] = $database->name;
                $users[$database->name] = $database->user->name;
                $hosts[$database->name] = $database->user->host->name;
            }

            if (count($options) > 0) {
                $this->database = $this->choice('Select a database', $options);
                $this->databaseModel = ServerDatabase::where('name', $this->database)->first();
                $this->user = $users[$this->database];
                $this->userModel = ServerUser::where('name', $this->user)->first();
                $this->host = $hosts[$this->database];
                $this->hostModel = ServerHost::where('name', $this->host)->first();
            }
        }
    }

    /**
     * Return if TTY is enabled / disbled.
     *
     * @return bool
     */
    public function getTTY()
    {
        if ($this->option('disable-tty')) {
            return false;
        }

        return true;
    }

    /**
     * Returns the path to the inventory script.
     *
     * @return string $path
     */
    public function getInventoryScript()
    {
        return base_path('inventory.sh');
    }

    /**
     * Returns the path to the provision playbook.
     *
     * @return string $path
     */
    public function getProvisionPlaybook()
    {
        return base_path('ansible/server.yml');
    }

    /**
     * Add line to the process buffer.
     *
     * @param string $message
     * @param boolean $debug
     * @return void
     */
    public static function addToProcessBuffer($message, $debug = true)
    {
        if ($debug) {
            echo $message;
        }

        self::$buffer .= $message;
    }

    /**
     * Returns the process buffer.
     *
     * @return string
     */
    public static function getProcessBuffer()
    {
        return self::$buffer;
    }
}
