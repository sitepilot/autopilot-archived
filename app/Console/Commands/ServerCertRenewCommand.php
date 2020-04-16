<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Traits\HasState;

class ServerCertRenewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:cert:renew
        {--host= : The host name (optional)}
        {--skip-tags= : Comma separated list of skipped tags (optional)}
        {--nova-batch-id= : The nova batch id (optional)}
        {--disable-tty : Disable TTY}
        {--debug : Show debug info}';

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

        $vars = [
            "host" => $this->host,
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name,state,' . HasState::getProvisionedIndex()
        ];

        $this->runPlaybook($this->hostModel, 'server/cert-renew.yml', $vars, $validations, "Failed to renew server certificates.");
    }
}
