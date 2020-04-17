<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Traits\HasState;

class ServerTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:test 
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
    protected $description = 'Test server configuration.';

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

        $authKeys = [];
        foreach ($host->authKeys as $key) {
            $authKeys[] = $key->getVar('key');
        }

        $vars = [
            "host" => $host->name,
            "admin" => $host->group->getVar('admin'),
            "admin_pass" => $host->getVar('admin_pass'),
            "auth_keys" => $authKeys
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name,state,' . HasState::getProvisionedIndex(),
            'admin' => 'required',
            'admin_pass' => 'required',
            'auth_keys' => 'array'
        ];

        $this->runPlaybook($host, 'server/test.yml', $vars, $validations, "Failed to test server.");
    }
}
