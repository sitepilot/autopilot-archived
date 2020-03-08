<?php

namespace App\Console\Commands;

use Exception;
use App\Console\Command;
use Symfony\Component\Process\Process;

class ServerProvisionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:provision 
        {--host= : The host name (optional)}
        {--tags= : Comma separated list of tags (optional)}
        {--skip-tags= : Comma separated list of skipped tags (optional)}
        {--disable-tty : Disable TTY}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Provision a server.';

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
            $cmd = ['ansible-playbook', '-i', $this->getInventoryScript(), $this->getProvisionPlaybook(), '--extra-vars', "host=$this->host"];

            if ($this->option('tags')) {
                $cmd = array_merge($cmd, ["--tags", $this->option('tags')]);
            }

            if ($this->option('skip-tags')) {
                $cmd = array_merge($cmd, ["--skip-tags", $this->option('skip-tags')]);
            }

            $process = new Process($cmd);
            $process->setTty($this->getTTY());
            $process->setTimeout(3600);

            $process->run(function ($type, $buffer) {
                if (Process::ERR === $type || preg_match("/failed=[1-9]\d*/", $buffer) || preg_match("/unreachable=[1-9]\d*/", $buffer)) {
                    echo $buffer;
                    throw new Exception("Failed to provision the server!");
                } else {
                    echo $buffer;
                }
            });
        }
    }
}
