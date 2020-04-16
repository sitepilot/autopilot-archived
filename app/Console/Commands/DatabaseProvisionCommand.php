<?php

namespace App\Console\Commands;

use Exception;
use Laravel\Nova\Nova;
use App\Console\Command;
use App\Notifications\CommandFailed;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Notification;

class DatabaseProvisionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:provision 
        {--database= : The database name (optional)}
        {--skip-tags= : Comma separated list of skipped tags (optional)}
        {--nova-batch-id= : The nova batch id (optional)}
        {--disable-tty : Disable TTY}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Provision a single database.';

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
        $this->askDatabase();

        $this->databaseModel->setStateProvisioning();

        $vars = [
            "host" => $this->host,
            "database" => $this->databaseModel->getVar('name'),
        ];

        $validations = [
            'database' => 'required|exists:server_databases,name',
        ];

        $this->runPlaybook($this->databaseModel, 'database/provision.yml', $vars, $validations, "Failed to provision database.");

        $this->databaseModel->setStateProvisioned();
    }
}
