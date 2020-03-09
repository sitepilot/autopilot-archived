<?php

namespace App\Nova\Actions;

use Exception;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;

class ServerTestAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Test Host';

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
    public $confirmButtonText = 'Test Host';

    /**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
    public $confirmText = 'Are you sure you want to test the host?';

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
                Artisan::call('server:test', [
                    '--host' => $host->name,
                    '--disable-tty' => true
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
        return [];
    }
}
