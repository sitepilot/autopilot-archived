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
        {--skip-tags= : Comma separated list of skipped tags (optional)}
        {--nova-batch-id= : The nova batch id (optional)}
        {--disable-tty : Disable TTY}';

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
        $this->askDatabase();

        $this->databaseModel->setStateDestroying();

        $vars = [
            "host" => $this->host,
            "database" => $this->databaseModel->getVar('name'),
        ];

        $validations = [
            'database' => 'required|exists:server_databases,name',
        ];

        $this->runPlaybook($this->databaseModel, 'database/destroy.yml', $vars, $validations, "Failed to destroy database.");

        $this->databaseModel->delete();
    }
}
