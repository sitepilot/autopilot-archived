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
        $user = $this->askUser();

        $apps = [];
        $domains = [];
        foreach ($user->apps as $app) {
            $apps[] = $app->getVar('name');
            $domains[] = $app->getVar('domain');
            $aliases = $app->getVar('aliases', '', []);
            foreach ($aliases as $alias) {
                $domains[] = $alias;
            }
        }

        $databases = [];
        foreach ($user->databases as $database) {
            $databases[] = $database->getVar('name');
        }

        $authKeys = [];
        foreach ($user->authKeys as $key) {
            $authKeys[] = $key->getVar('key');
        }

        $vars = [
            "host" => $user->host->name,
            "user" => $user->name,
            "mysql_password" => $user->getVar('mysql_password'),
            "isolated" => $user->getVar('isolated'),
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

        $this->runPlaybook($user, 'user/test.yml', $vars, $validations, "Failed to test user: $user->name.");
    }
}
