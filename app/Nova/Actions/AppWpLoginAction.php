<?php

namespace App\Nova\Actions;

use Exception;
use Laravel\Nova\Nova;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;

class AppWpLoginAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'WP: Login';

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
    public $confirmButtonText = 'Login';

    /**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
    public $confirmText = 'Are you sure you want to login to WordPress?';

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
                Artisan::call('app:wp:login', [
                    'login' => $fields->login,
                    '--app' => $app->name,
                    '--nova-batch-id' => $this->batchId,
                    '--disable-tty' => true
                ]);

                $event = Nova::actionEvent();
                $event = $event::where('batch_id', $this->batchId)
                    ->where('model_type', $app->getMorphClass())
                    ->where('model_id', $app->getKey())
                    ->first();

                if (!empty($event->exception) && filter_var($event->exception, FILTER_VALIDATE_URL)) {
                    return Action::openInNewTab($event->exception);
                }
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
        return [
            Text::make('Username', 'login')
                ->help('Optional, defaults to admin username (wordpress.admin_user).')
        ];
    }
}
