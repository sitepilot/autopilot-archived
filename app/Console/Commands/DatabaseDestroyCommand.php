<?php

namespace App\Console\Commands;

use Exception;
use Laravel\Nova\Nova;
use App\Console\Command;
use App\Notifications\CommandFailed;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Notification;

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

        if ($this->host && $this->user && $this->database) {
            $database = $this->databaseModel;
            $database->setStateDestroying();

            $cmd = ['ansible-playbook', '-i', $this->getInventoryScript(), $this->getProvisionPlaybook()];

            $extraVars = "host=$this->host destroy_database_user=$this->user destroy_database=$this->database";
            $cmd = array_merge($cmd, ["--extra-vars", $extraVars]);

            $tags = "destroy-database";
            if ($this->option('tags')) {
                $tags .= "," . $this->option('tags');
            }
            $cmd = array_merge($cmd, ["--tags", $tags]);

            if ($this->option('skip-tags')) {
                $cmd = array_merge($cmd, ["--skip-tags", $this->option('skip-tags')]);
            }

            $process = new Process($cmd);
            $process->setTty($this->getTTY());
            $process->setTimeout(3600);

            $process->run(function ($type, $buffer) use ($database) {
                if (Process::ERR === $type || preg_match("/failed=[1-9]\d*/", $buffer) || preg_match("/unreachable=[1-9]\d*/", $buffer)) {
                    $database->setStateError();
                    self::addToProcessBuffer($buffer);
                    Notification::route('slack', env('SLACK_HOOK'))
                        ->notify(new CommandFailed("Failed to destroy database.", self::getProcessBuffer()));
                    throw new Exception("Failed to destroy the database!\n" . self::getProcessBuffer());
                } else {
                    self::addToProcessBuffer($buffer);
                }
            });

            if ($this->option('nova-batch-id')) {
                $event = Nova::actionEvent();
                $event::where('batch_id', $this->option('nova-batch-id'))
                    ->where('model_type', $database->getMorphClass())
                    ->where('model_id', $database->getKey())
                    ->update(['exception' => self::getProcessBuffer()]);
            }

            $database->delete();
        } else {
            throw new Exception("Could not find database.");
        }
    }
}
