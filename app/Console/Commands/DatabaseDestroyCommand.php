<?php

namespace App\Console\Commands;

use App\Console\Command;

class DatabaseDestroyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:destroy 
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
    protected $description = 'Destroy a single database.';

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

        $database->setStateDestroying();

        $vars = [
            "host" => $database->host->name,
            "database" => $database->getVar('name'),
        ];

        $validations = [
            'database' => 'required|exists:server_databases,name',
        ];

        $this->runPlaybook($database, 'database/destroy.yml', $vars, $validations, "Failed to destroy database.");

        $database->delete();
    }
}
