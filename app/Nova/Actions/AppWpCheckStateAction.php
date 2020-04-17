<?php

namespace App\Nova\Actions;

use Exception;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;

class AppWpCheckStateAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Check WordPress State';

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
    public $confirmButtonText = 'Check WordPress State';

    /**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
    public $confirmText = 'Are you sure you want to check the WordPress state?';

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
                Artisan::call('app:wp:check-state', [
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
