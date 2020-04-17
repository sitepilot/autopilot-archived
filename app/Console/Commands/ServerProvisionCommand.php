<?php

namespace App\Console\Commands;

use App\Console\Command;

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
        {--nova-batch-id= : The nova batch id (optional)}
        {--disable-tty : Disable TTY}
        {--debug : Show debug info}';

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
        $host = $this->askHost();

        $host->setStateProvisioning();

        $vars = [
            "host" => $host->name,
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name'
        ];

        $this->runPlaybook($host, 'server/provision.yml', $vars, $validations, "Failed to provision server.");

        $host->setStateProvisioned();
    }
}
