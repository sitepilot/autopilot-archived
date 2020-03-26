<?php

namespace App\Console\Commands;

use Exception;
use App\ServerUser;
use Laravel\Nova\Nova;
use App\Console\Command;
use Symfony\Component\Process\Process;

class UserProvisionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:provision 
        {--user= : The user name (optional)}
        {--tags= : Comma separated list of tags (optional)}
        {--skip-tags= : Comma separated list of skipped tags (optional)}
        {--nova-batch-id= : The nova batch id (optional)}
        {--disable-tty : Disable TTY}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Provision a single user.';

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
        $this->askUser();

        if ($this->host && $this->user) {
            $cmd = ['ansible-playbook', '-i', $this->getInventoryScript(), $this->getProvisionPlaybook()];

            $extraVars = "host=$this->host";
            if ($this->user != 'all') {
                $extraVars .= " user_filter=$this->user";
            }
            $cmd = array_merge($cmd, ["--extra-vars", $extraVars]);

            $tags = "users";
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
                    throw new Exception("Failed to provision the user!\n" . self::getProcessBuffer());
                } else {
                    self::addToProcessBuffer($buffer);
                }
            });

            if ($this->option('nova-batch-id')) {
                $model = ServerUser::where('name', $this->user)->first();
                $event = Nova::actionEvent();
                $event::where('batch_id', $this->option('nova-batch-id'))
                    ->where('model_type', $model->getMorphClass())
                    ->where('model_id', $model->getKey())
                    ->update(['exception' => self::getProcessBuffer()]);
            }
        } else {
            throw new Exception("Could not find user.");
        }
    }
}
