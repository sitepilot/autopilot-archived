<?php

namespace App\Console;

use Exception;
use App\ServerApp;
use App\ServerHost;
use App\ServerUser;
use Laravel\Nova\Nova;
use App\ServerDatabase;
use App\Notifications\CommandFailed;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Illuminate\Console\Command as ConsoleCommand;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
        if ($this->option('host')) {
            $this->hostModel = $this->option('host') == 'test' ? ServerHost::first() : ServerHost::where('name', $this->option('host'))->first();
            if ($this->hostModel) {
                $this->host = $this->hostModel->name;
            }
        } else {
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

        if (!$this->host) {
            throw new Exception("Could not find host.");
        }
    }

    /**
     * Ask for a user.
     *
     * @return void
     */
    public function askUser()
    {
        if ($this->option('user')) {
            $this->userModel = $this->option('user') == 'test' ? ServerUser::first() : ServerUser::where('name', $this->option('user'))->first();
            if ($this->userModel) {
                $this->user = $this->userModel->name;
                if ($this->hostModel = $this->userModel->host) {
                    $this->host = $this->hostModel->name;
                }
            }
        } else {
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

        if (!$this->host || !$this->user) {
            throw new Exception("Could not find user.");
        }
    }

    /**
     * Ask for a app.
     *
     * @return void
     */
    public function askApp()
    {
        if ($this->option('app')) {
            $this->appModel = $this->option('app') == 'test' ? ServerApp::first() : ServerApp::where('name', $this->option('app'))->first();
            if ($this->appModel) {
                $this->app = $this->appModel->name;

                if ($this->userModel = $this->appModel->user) {
                    $this->user = $this->userModel->name;

                    if ($this->hostModel = $this->userModel->host) {
                        $this->host = $this->hostModel->name;
                    }
                }
            }
        } else {
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

        if (!$this->host || !$this->user || !$this->app) {
            throw new Exception("Could not find app.");
        }
    }

    /**
     * Ask for a database.
     *
     * @return void
     */
    public function askDatabase()
    {
        if ($this->option('database')) {
            $this->databaseModel = $this->option('database') == 'test' ? ServerDatabase::first() : ServerDatabase::where('name', $this->option('database'))->first();
            if ($this->databaseModel) {
                $this->database = $this->databaseModel->name;

                if ($this->userModel = $this->databaseModel->user) {
                    $this->user = $this->userModel->name;

                    if ($this->hostModel = $this->userModel->host) {
                        $this->host = $this->hostModel->name;
                    }
                }
            }
        } else {
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

        if (!$this->host || !$this->user || !$this->database) {
            throw new Exception("Could not find database.");
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

        return Process::isTtySupported();
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

    /**
     * Run app playbook.
     * 
     * @return void
     * @throws Exception
     */
    public function runPlaybook($model, $playbook, $vars = [], $validations = [], $failedMessage = '')
    {
        // Validate playbook vars
        $validator = Validator::make($vars, $validations, [
            'exists' => 'The :key is invalid or not provisioned.',
            'required' => 'The :key configuration parameter is required.'
        ]);

        if ($validator->fails()) {
            $validationErrors = "";
            foreach ($validator->errors()->all() as $msg) {
                $validationErrors .= "\n$msg";
            }
            throw new Exception("$failedMessage\n$validationErrors");
        }

        // Add sitepilot_managed var
        $vars['sitepilot_managed'] = "WARNING: This file is managed by Sitepilot, any changes will be overwritten (updated at: {{ansible_date_time.date}} {{ansible_date_time.time}}).";

        // Prepare command
        $cmd = ['ansible-playbook', '-i', $this->getInventoryScript(), base_path("ansible/playbooks/$playbook"), "--extra-vars", json_encode($vars)];

        // Add tags
        if ($this->option('tags')) {
            $cmd = array_merge($cmd, ['--tags', $this->option('tags')]);
        }

        // Add skip tags
        if ($this->option('skip-tags')) {
            $cmd = array_merge($cmd, ['--skip-tags', $this->option('skip-tags')]);
        }

        // Add debug parameter
        if ($this->option('debug')) {
            $cmd = array_merge($cmd, ['-v']);
        }

        // Run process
        $process = new Process($cmd);
        $process->setTty($this->getTTY())->setTimeout(3600);

        try {
            $process->mustRun(function ($type, $buffer) use ($model, $failedMessage) {
                if (Process::ERR === $type || preg_match("/failed=[1-9]\d*/", $buffer) || preg_match("/unreachable=[1-9]\d*/", $buffer)) {
                    $model->setStateError();

                    self::addToProcessBuffer($buffer);

                    Notification::route('slack', env('SLACK_HOOK'))
                        ->notify(new CommandFailed($failedMessage, self::getProcessBuffer()));

                    throw new Exception("$failedMessage\n" . self::getProcessBuffer());
                } else {
                    self::addToProcessBuffer($buffer);
                }
            });
        } catch (ProcessFailedException $e) {
            throw new Exception($failedMessage);
        }

        // Update Nova batch status
        if ($this->option('nova-batch-id')) {
            $event = Nova::actionEvent();
            $event::where('batch_id', $this->option('nova-batch-id'))
                ->where('model_type', $model->getMorphClass())
                ->where('model_id', $model->getKey())
                ->update(['exception' => self::getProcessBuffer()]);
        }
    }
}
