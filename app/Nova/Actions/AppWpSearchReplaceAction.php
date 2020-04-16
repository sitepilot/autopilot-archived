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

class AppWpSearchReplaceAction extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Search & Replace WordPress';

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
    public $confirmButtonText = 'Search & Replace';

    /**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
    public $confirmText = 'Are you sure you want to search and replace in the WordPress database?';

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
                Artisan::call('app:wp:search-replace', [
                    'search' => $fields->search,
                    'replace' => $fields->replace,
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
        return [
            Text::make('Search', 'search')
                ->rules(['required', 'min:3'])
                ->help("The string to search for."),

            Text::make('Replace', 'replace')
                ->rules(['required', 'min:3'])
                ->help("The replacement.")
        ];
    }
}
