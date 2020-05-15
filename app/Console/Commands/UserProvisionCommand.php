<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Traits\HasState;
use Illuminate\Support\Facades\Artisan;

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
        {--job-status-id= : The job status id (optional)}
        {--disable-tty : Disable TTY}
        {--debug : Show debug info}';

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
        $user = $this->askUser();

        $authKeys = [];
        foreach ($user->authKeys as $key) {
            $authKeys[] = $key->vars;
        }

        $vars = [
            "host" => $user->host->name,
            "user" => $user->name,
            "full_name" => $user->getVar('full_name'),
            "email" => $user->getVar('email'),
            "password" => $user->getVar('password'),
            "mysql_password" => $user->getVar('mysql_password'),
            "isolated" => $user->getVar('isolated'),
            "auth_keys" => $authKeys
        ];

        $validations = [
            'host' => 'required|exists:server_hosts,name,state,' . HasState::getProvisionedIndex(),
            'user' => 'required|exists:server_users,name',
            'full_name' => 'required|min:3',
            'email' => 'email',
            'password' => 'required|min:6',
            'mysql_password' => 'required|min:6',
            'isolated' => 'required|boolean',
            'auth_keys' => 'array',
            'auth_keys.*.name' => 'required',
            'auth_keys.*.key' => 'required'
        ];

        $user->setStateProvisioning();

        $this->runPlaybook($user, 'user/provision.yml', $vars, $validations, "Failed to provision user: $user->name.");

        foreach ($user->apps as $app) {
            Artisan::call('app:provision', [
                '--app' => $app->name
            ]);
        }

        foreach ($user->databases as $database) {
            Artisan::call('database:provision', [
                '--database' => $database->name
            ]);
        }
    }
}
