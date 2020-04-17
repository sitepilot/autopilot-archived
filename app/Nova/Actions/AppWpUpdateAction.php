<?php

namespace App\Nova\Actions;

use App\Traits\QueuedAction;
use Exception;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppWpUpdateAction extends Action implements ShouldQueue
{
    use InteractsWithQueue, Queueable, QueuedAction;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Update WordPress';

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
    public $confirmButtonText = 'Update WordPress';

    /**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
    public $confirmText = 'Are you sure you want to update WordPress?';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $app) {
            try {
                Artisan::call('app:wp:update', [
                    '--app' => $app->name,
                    '--nova-batch-id' => $this->batchId,
                    '--disable-tty' => true
                ]);
            } catch (Exception $e) {
                $this->markAsFailed($app, $e->getMessage());
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
