<?php

namespace App\Nova;

use App\Nova\ServerUser;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Actions\DatabaseDestroyAction;
use App\Nova\Actions\DatabaseProvisionAction;

class ServerDatabase extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\ServerDatabase';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Servers';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'description'
    ];

    /**
     * Returns the menu label.
     *
     * @return string
     */
    public static function label()
    {
        return 'Databases';
    }

    /**
     * Returns the menu position.
     *
     * @return int
     */
    public static function menuPosition()
    {
        return 50;
    }

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string|null
     */
    public function subtitle()
    {
        return $this->description;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Name', 'name')
                ->sortable()
                ->hideWhenCreating()
                ->readonly(),

            BelongsTo::make('User', 'user', ServerUser::class)
                ->help('This field will be ignored when an App is selected above.')
                ->searchable()
                ->nullable()
                ->sortable()
                ->rules('required_without:app')
                ->readonly(function ($request) {
                    return $request->isUpdateOrUpdateAttachedRequest();
                }),

            BelongsTo::make('App', 'app', ServerApp::class)
                ->help('User will be selected based on the App owner.')
                ->searchable()
                ->rules('required_without:user')
                ->nullable()
                ->sortable()
                ->readonly(function ($request) {
                    return $request->isUpdateOrUpdateAttachedRequest();
                }),

            Text::make('Client', 'client')
                ->exceptOnForms()
                ->resolveUsing(function ($client) {
                    if (isset($client->name)) {
                        return "<a href='" . url(config("nova.path") . "/resources/clients/" . $client->id) . "' 
                            class='no-underline dim text-primary font-bold'>" . $client->name . "</a>";
                    }
                    return null;
                })->asHtml(),

            Text::make('Description', 'description')
                ->sortable(),

            Select::make('State')->options(
                \App\ServerDatabase::getStates()
            )
                ->exceptOnForms()
                ->displayUsingLabels(),

            Code::make('Database Configuration', 'vars')
                ->rules(['required', 'json'])
                ->json()
                ->onlyOnForms()
                ->hideWhenCreating(),

            Code::make('Default Configuration', 'default_vars')
                ->readonly()
                ->json()
                ->onlyOnForms()
                ->hideWhenCreating(),

            Code::make('Database Configuration', 'secure_vars')
                ->readonly()
                ->json()
                ->onlyOnDetail(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new DatabaseProvisionAction,
            new DatabaseDestroyAction
        ];
    }
}
