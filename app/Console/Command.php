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
    /**
     * Ask for a host.
     *
     * @return void
     */
    public function askHost()
    {
        if ($this->option('host')) {
            $host = $this->option('host') == '#first-item' ? ServerHost::first() : ServerHost::where('name', $this->option('host'))->first();
        } else {
            $hosts = ServerHost::get();

            $options = [];
            foreach ($hosts as $host) {
                $options[] = $host->name;
            }

            if (count($options) > 0) {
                $host = ServerHost::where('name', $this->choice('Select host', $options))->first();
            }
        }

        if ($host) {
            return $host;
        }

        throw new Exception("Could not find host.");
    }

    /**
     * Ask for a user.
     *
     * @return void
     */
    public function askUser()
    {
        if ($this->option('user')) {
            $user = $this->option('user') == '#first-item' ? ServerUser::first() : ServerUser::where('name', $this->option('user'))->first();
        } else {
            $users = ServerUser::get();

            $options = [];
            foreach ($users as $user) {
                $options[] = $user->name;
            }

            if (count($options) > 0) {
                $user = ServerUser::where('name', $this->choice('Select user', $options))->first();
            }
        }

        if ($user) {
            return $user;
        }

        throw new Exception("Could not find user.");
    }

    /**
     * Ask for a app.
     *
     * @return void
     */
    public function askApp()
    {
        if ($this->option('app')) {
            $app = $this->option('app') == '#first-item' ? ServerApp::first() : ServerApp::where('name', $this->option('app'))->first();
        } else {
            $apps = ServerApp::get();

            $options = [];
            foreach ($apps as $app) {
                $options[] = $app->name;
            }

            if (count($options) > 0) {
                $app = ServerApp::where('name', $this->choice('Select app', $options))->first();
            }
        }

        if ($app) {
            return $app;
        }

        throw new Exception("Could not find app.");
    }

    /**
     * Ask for a database.
     *
     * @return void
     */
    public function askDatabase()
    {
        if ($this->option('database')) {
            $database = $this->option('database') == '#first-item' ? ServerDatabase::first() : ServerDatabase::where('name', $this->option('database'))->first();
        } else {
            $databases = ServerDatabase::get();

            $options = [];
            foreach ($databases as $database) {
                $options[] = $database->name;
            }

            if (count($options) > 0) {
                $database = ServerDatabase::where('name', $this->choice('Select database', $options))->first();
            }
        }

        if ($database) {
            return $database;
        }

        throw new Exception("Could not find database.");
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
     * Run playbook.
     * 
     * @return string $processBuffer
     * @throws Exception
     */
    public function runPlaybook($model, $playbook, $vars = [], $validations = [], $failedMessage = '', $setErrorState = true)
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
        $batchId = $this->option('nova-batch-id');
        $processBuffer = '';

        try {
            $process->mustRun(function ($type, $buffer) use (&$processBuffer, $model, $failedMessage, $setErrorState, $batchId) {
                if (Process::ERR === $type || preg_match("/failed=[1-9]\d*/", $buffer) || preg_match("/unreachable=[1-9]\d*/", $buffer)) {
                    if ($setErrorState) $model->setStateError();

                    if (empty($batchId)) echo $buffer;
                    $processBuffer .= $buffer;

                    Notification::route('slack', env('SLACK_HOOK'))
                        ->notify(new CommandFailed($failedMessage, $processBuffer));

                    throw new Exception("$failedMessage\n" . $processBuffer);
                } else {
                    if (empty($batchId)) echo $buffer;
                    $processBuffer .= $buffer;
                }
            });
        } catch (ProcessFailedException $e) {
            throw new Exception($failedMessage);
        }

        // Update Nova batch status
        if ($batchId) {
            $result =  $this->findBetween($processBuffer, '[autopilot-result]', '[/autopilot-result]');
            $event = Nova::actionEvent();
            $event::where('batch_id', $batchId)
                ->where('model_type', $model->getMorphClass())
                ->where('model_id', $model->getKey())
                ->update(['exception' => !empty($result) ? $result : $processBuffer]);
        }

        return $processBuffer;
    }

    /**
     * Finds a substring between two strings.
     * 
     * @param  string $string The string to be searched
     * @param  string $start The start of the desired substring
     * @param  string $end The end of the desired substring
     * @param  bool   $greedy Use last instance of`$end` (default: false)
     * @return string
     */
    function findBetween(string $string, string $start, string $end, bool $greedy = false)
    {
        $start = preg_quote($start, '/');
        $end   = preg_quote($end, '/');

        $format = '/(%s)(.*';
        if (!$greedy) $format .= '?';
        $format .= ')(%s)/';

        $pattern = sprintf($format, $start, $end);
        preg_match($pattern, $string, $matches);

        if (isset($matches[2])) {
            return $matches[2];
        }

        return '';
    }
}
