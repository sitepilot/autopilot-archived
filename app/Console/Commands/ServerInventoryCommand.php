<?php

namespace App\Console\Commands;

use App\Host;
use App\ServerGroup;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class ServerInventoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:inventory 
        {--host= : The host name (optional)}
        {--list : Comma separated list of tags (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Outputs the server inventory for Ansible.';

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
        $groups = ServerGroup::get();
        $output = [];
        $meta = [];

        foreach ($groups as $group) {
            $groupOutput = [
                'hosts' => [],
                'vars' => $group->vars
            ];

            foreach ($group->hosts as $host) {
                $groupOutput['hosts'][] = $host->name;

                $hostvars = $host->vars;
                if (isset($hostvars['ansible_ssh_private_key_file'])) {
                    $hostvars['ansible_ssh_private_key_file'] = substr($hostvars['ansible_ssh_private_key_file'], 0, 1) == '/' ? $hostvars['ansible_ssh_private_key_file'] : storage_path("app/" . $hostvars['ansible_ssh_private_key_file']);
                    chmod($hostvars['ansible_ssh_private_key_file'], 0600);
                }

                if (isset($hostvars['ansible_ssh_public_key_file'])) {
                    $hostvars['ansible_ssh_public_key_file'] = substr($hostvars['ansible_ssh_public_key_file'], 0, 1) == '/' ? $hostvars['ansible_ssh_public_key_file'] : storage_path("app/" . $hostvars['ansible_ssh_public_key_file']);
                    chmod($hostvars['ansible_ssh_public_key_file'], 0600);
                }

                $hostvars['users'] = [];
                foreach ($host->users as $user) {
                    $uservars = $user->vars;
                    $uservars['apps'] = [];
                    foreach ($user->apps as $app) {
                        $uservars['apps'][] = $app->vars;
                    }

                    $uservars['databases'] = [];
                    foreach ($user->databases as $database) {
                        $uservars['databases'][] = $database->vars;
                    }

                    $uservars['auth_keys'] = [];
                    foreach ($user->authKeys as $key) {
                        $uservars['auth_keys'][] = $key->vars;
                    }

                    $hostvars['users'][] = $uservars;
                }

                $hostvars['firewall'] = [];
                foreach ($host->firewallRules as $rule) {
                    $hostvars['firewall'][] = $rule->vars;
                }

                $hostvars['auth_keys'] = [];
                foreach ($host->authKeys as $key) {
                    $hostvars['auth_keys'][] = $key->vars;
                }

                $meta['hostvars'][$host->name] = $hostvars;
            }

            $output[Str::camel($group->name)] = $groupOutput;
        }

        $output['_meta'] = $meta;

        print json_encode($output);
    }
}
