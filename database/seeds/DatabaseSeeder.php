<?php

use App\User;
use App\ServerApp;
use App\ServerHost;
use App\ServerUser;
use App\ServerGroup;
use App\ServerAuthKey;
use App\ServerDatabase;
use App\ServerFirewallRule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!User::count()) {
            factory(User::class)->create([
                'name' => 'Admin',
                'email' => 'admin@sitepilot.io',
                'password' => Hash::make('supersecret')
            ]);
        }

        if (!ServerGroup::count()) {
            // Create server group
            $serverGroup = new ServerGroup;
            $serverGroup->name  = 'ams-web';
            $serverGroup->save();

            // Create firewall rules
            $fwRuleHttp = new ServerFirewallRule;
            $fwRuleHttp->name = 'http';
            $fwRuleHttp->vars = ['port' => '80'];
            $fwRuleHttp->save();

            $fwRuleHttps = new ServerFirewallRule;
            $fwRuleHttps->name = 'https';
            $fwRuleHttps->vars = ['port' => '443'];
            $fwRuleHttps->save();

            $fwRuleSSH = new ServerFirewallRule;
            $fwRuleSSH->name = 'ssh';
            $fwRuleSSH->vars = ['port' => '22'];
            $fwRuleSSH->save();

            $fwRuleLitespeed = new ServerFirewallRule;
            $fwRuleLitespeed->name = 'litespeed';
            $fwRuleLitespeed->vars = ['port' => '2083'];
            $fwRuleLitespeed->save();

            $fwRuleMysql = new ServerFirewallRule;
            $fwRuleMysql->name = 'mysql';
            $fwRuleMysql->vars = ['port' => '3306'];
            $fwRuleMysql->save();

            $authKey = new ServerAuthKey;
            $authKey->name = 'test@server';
            $authKey->vars = ['key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDYWDiu9nNJJFWXo/KQZaS/wZB27Ig83m9GW30L7mOhV7GeMtDQ82idl5T1jfgB2uXedc1dbY0xLO2if9wIovaKXP5ZWoh7f3Mvx3WAuhhYq1rsqEhf282Q59KobCmG99Tni9W23FT3aNF8I+atLJF6uUU09xKK5tzoWLS7eiAbsrxbm10KcWB/AbpCJQQJn3Dulno1zCI1Z5FyKlU5lMWgDAUJO/CxyC4rtkAzexz/KAjV7OvPmQmI74xldxjQA9S+LfAu5Zx/Qs4vVb+cWOjCXyjaUMT4oF8lpkFvw2P2o9NCgdx8yoMJJGsiqCP6yn+3KDtAq6x5J8EgsxKpzGud test@server'];
            $authKey->save();

            // Create server localhost
            $host = new ServerHost;
            $host->group_id = $serverGroup->id;
            $host->vars = [
                'ansible_ssh_host' => env('APP_TEST_HOST'),
                'ansible_ssh_port' => env('APP_TEST_PORT'),
                'ansible_ssh_private_key_file' => '/var/www/html/vagrant/ssh/test_key',
                'ansible_ssh_public_key_file' => '/var/www/html/vagrant/ssh/test_key.pub',
                'ansible_ssh_common_args' => '-o StrictHostKeyChecking=no',
                'autopilot_host' => env('APP_TEST_AUTOPILOT_HOST'),
            ];
            $host->save();
            $host->firewallRules()->attach([
                $fwRuleHttp->id,
                $fwRuleHttps->id,
                $fwRuleSSH->id,
                $fwRuleLitespeed->id,
                $fwRuleMysql->id
            ]);
            $host->authKeys()->attach([
                $authKey->id
            ]);

            // Create server user
            $user = new ServerUser;
            $host->vars = [
                'admin_access' => true,
            ];
            $user->host_id = $host->id;
            $user->save();
            $user->authKeys()->attach([
                $authKey->id
            ]);

            // Create server app
            $app = new ServerApp;
            $app->user_id = $user->id;
            $app->vars = [
                'aliases' => [
                    'example-alias1.com',
                    'example-alias2.com'
                ]
            ];
            $app->save();

            // Create server database
            $db = new ServerDatabase;
            $db->user_id = $user->id;
            $db->app_id = $app->id;
            $db->save();
        }
    }
}
