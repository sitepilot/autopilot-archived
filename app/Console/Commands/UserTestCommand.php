<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Traits\HasState;

class UserTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:test 
        {--user= : The user name (optional)}
        {--skip-tags= : Comma separated list of skipped tags (optional)}
        {--nova-batch-id= : The nova batch id (optional)}
        {--disable-tty : Disable TTY}
        {--debug : Show debug info}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test a single user.';

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

        $apps = [];
        $domains = [];
        foreach ($this->userModel->apps as $app) {
            $apps[] = $app->getVar('name');
            $domains[] = $app->getVar('domain');
            $aliases = $app->getVar('aliases', '', []);
            foreach ($aliases as $alias) {
                $domains[] = $alias;
            }
        }

        $databases = [];
        foreach ($this->userModel->databases as $database) {
            $databases[] = $database->getVar('name');
        }

        $authKeys = [];
        foreach ($this->userModel->authKeys as $key) {
            $authKeys[] = $key->getVar('key');
        }

        $vars = [
            "host" => $this->host,
            "user" => $this->user,
            "mysql_password" => $this->userModel->getVar('mysql_password'),
            "isolated" => $this->userModel->getVar('isolated'),
            "apps" => $apps,
            "auth_keys" => $authKeys,
            "databases" => $databases,
            "domains" => $domains
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name,state,' . HasState::getProvisionedIndex(),
            'user' => 'required|exists:server_users,name,state,' . HasState::getProvisionedIndex(),
            'mysql_password' => 'required|min:6',
            'isolated' => 'required|boolean',
            'apps' => 'array',
            'auth_keys' => 'array',
            'databases' => 'array',
            'domains' => 'array',
        ];

        $this->runPlaybook($this->userModel, 'user/test.yml', $vars, $validations, "Failed to test user.");
    }
}
