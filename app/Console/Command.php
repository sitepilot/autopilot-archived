<?php

namespace App\Console;

use App\Host;
use App\ServerHost;
use Illuminate\Console\Command as ConsoleCommand;

class Command extends ConsoleCommand
{
    protected $host = null;
    protected $user = null;

    /**
     * Ask for a host.
     *
     * @return void
     */
    public function askHost()
    {
        if ($this->host = $this->option('host')) {
            return;
        }

        if (!$this->host) {
            $hosts = ServerHost::get();
            $options = [];

            foreach ($hosts as $host) {
                $options[] = $host->name;
            }

            $this->host = $this->choice('Select a host', $options);
        }
    }

    /**
     * Return if TTY is enabled / disbled.
     *
     * @return bool
     */
    public function getTTY()
    {
        if ($this->option('disable-tty')) {
            return false;
        }

        return true;
    }

    public function getInventoryScript()
    {
        return base_path('inventory.sh');
    }

    public function getProvisionPlaybook()
    {
        return base_path('ansible/server.yml');
    }
}
