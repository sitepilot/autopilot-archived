<?php

namespace App\Console\Commands;

use App\Console\Command;

class DatabaseProvisionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:provision 
        {--database= : The database name (optional)}
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
        $database = $this->askDatabase();

        $database->setStateProvisioning();

        $vars = [
            "host" => $database->host->name,
            "database" => $database->getVar('name'),
        ];

        $validations = [
            'database' => 'required|exists:server_databases,name',
        ];

        $this->runPlaybook($database, 'database/provision.yml', $vars, $validations, "Failed to provision database: $database->name.");

        $database->setStateProvisioned();
    }
}
