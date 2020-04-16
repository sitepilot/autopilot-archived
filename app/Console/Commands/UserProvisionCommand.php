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
        $this->askUser();

        $this->userModel->setStateProvisioning();

        $authKeys = [];
        foreach ($this->userModel->authKeys as $key) {
            $authKeys[] = $key->vars;
        }

        $vars = [
            "host" => $this->host,
            "user" => $this->user,
            "full_name" => $this->userModel->getVar('full_name'),
            "email" => $this->userModel->getVar('email'),
            "password" => $this->userModel->getVar('password'),
            "mysql_password" => $this->userModel->getVar('mysql_password'),
            "isolated" => $this->userModel->getVar('isolated'),
            "auth_keys" => $authKeys
        ];

        $validations = [
            #'host' => 'required|exists:server_hosts,name,state,' . HasState::getProvisionedIndex(),
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

        $this->runPlaybook($this->userModel, 'user/provision.yml', $vars, $validations, "Failed to provision user.");

        $this->userModel->setStateProvisioned();

        foreach ($this->userModel->apps as $app) {
            Artisan::call('app:provision', [
                '--app' => $app->name
            ]);
        }

        foreach ($this->userModel->databases as $database) {
            Artisan::call('database:provision', [
                '--database' => $database->name
            ]);
        }
    }
}
