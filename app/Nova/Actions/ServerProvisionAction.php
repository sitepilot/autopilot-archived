<?php

namespace App\Nova\Actions;

use Exception;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;

class ServerProvisionAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Provision Server';

    /**
     * Indicates if this action is available on the resource's table row.
     *
     * @var bool
     */
    public $showOnTableRow = true;

    /**
     * The text to be used for the action's confirm button.
     *
     * @var string
     */
    public $confirmButtonText = 'Provision Server';

    /**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
    public $confirmText = 'Are you sure you want to provision the server?';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $host) {
            try {
                Artisan::call('server:provision', [
                    '--host' => $host->name,
                    '--disable-tty' => true,
                    '--tags' => $fields->tags,
                    '--skip-tags' => $fields->skip_tags
                ]);
            } catch (Exception $e) {
                $this->markAsFailed($host, $e->getMessage());
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $tags = "swap, config, upgrade, install, root, admin, sshd, ssmtp, firewall, docker, mysql, redis, olsws, php, composer, wpcli, pma, health, fail2ban, nodejs, certbot, users";

        return [
            Text::make('Tags', 'tags')
                ->help("Available tags: $tags"),

            Text::make('Skip Tags', 'skip_tags')
                ->help("Available tags: $tags")
        ];
    }
}
