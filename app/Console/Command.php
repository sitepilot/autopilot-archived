<?php

namespace App\Console;

use App\ServerHost;
use Illuminate\Console\Command as ConsoleCommand;

class Command extends ConsoleCommand
{
    protected $host = null;
    protected $user = null;
    protected static $buffer = null;

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

    /**
     * Returns the path to the inventory script.
     *
     * @return string
     */
    public function getInventoryScript()
    {
        return base_path('inventory.sh');
    }

    /**
     * Returns the path to the provision server playbook.
     *
     * @return string
     */
    public function getProvisionPlaybook()
    {
        return base_path('ansible/server.yml');
    }

    /**
     * Add line to the process buffer.
     *
     * @param string $message
     * @param boolean $debug
     * @return void
     */
    public static function addToProcessBuffer($message, $debug = true)
    {
        if ($debug) {
            echo $message;
        }

        self::$buffer .= $message;
    }

    /**
     * Returns the process buffer.
     *
     * @return string
     */
    public static function getProcessBuffer()
    {
        return self::$buffer;
    }
}
