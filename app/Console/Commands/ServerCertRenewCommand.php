<?php

namespace App\Console\Commands;

use Exception;
use Laravel\Nova\Nova;
use App\Console\Command;
use App\Notifications\CommandFailed;
use App\Notifications\CommandSuccess;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Notification;

class ServerCertRenewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:cert:renew
        {--host= : The host name (optional)}
        {--tags= : Comma separated list of tags (optional)}
        {--skip-tags= : Comma separated list of skipped tags (optional)}
        {--nova-batch-id= : The nova batch id (optional)}
        {--disable-tty : Disable TTY}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew certificates.';

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
        $this->askHost();

        if ($this->host) {
            $host = $this->hostModel;

            $cmd = ['ansible-playbook', '-i', $this->getInventoryScript(), $this->getProvisionPlaybook()];

            $extraVars = "host=$this->host";
            $cmd = array_merge($cmd, ["--extra-vars", $extraVars]);

            $tags = "provision-cert-renew";
            if ($this->option('tags')) {
                $tags .= "," . $this->option('tags');
            }
            $cmd = array_merge($cmd, ["--tags", $tags]);

            if ($this->option('skip-tags')) {
                $cmd = array_merge($cmd, ["--skip-tags", $this->option('skip-tags')]);
            }

            $process = new Process($cmd);
            $process->setTty($this->getTTY());
            $process->setTimeout(3600);

            $process->run(function ($type, $buffer) {
                if (Process::ERR === $type || preg_match("/failed=[1-9]\d*/", $buffer) || preg_match("/unreachable=[1-9]\d*/", $buffer)) {
                    self::addToProcessBuffer($buffer);
                    Notification::route('slack', env('SLACK_HOOK'))
                        ->notify(new CommandFailed("Failed to renew certificates.", self::getProcessBuffer()));
                    throw new Exception("Failed to renew certificates!\n" . self::getProcessBuffer());
                } else {
                    self::addToProcessBuffer($buffer);
                }
            });

            if ($this->option('nova-batch-id')) {
                $event = Nova::actionEvent();
                $event::where('batch_id', $this->option('nova-batch-id'))
                    ->where('model_type', $host->getMorphClass())
                    ->where('model_id', $host->getKey())
                    ->update(['exception' => self::getProcessBuffer()]);
            }

            if ($this->option('disable-tty')) {
                Notification::route('slack', env('SLACK_HOOK'))
                    ->notify(new CommandSuccess("Successfully renewed certificates.", self::getProcessBuffer()));
            }
        } else {
            throw new Exception("Could not find host.");
        }
    }
}
