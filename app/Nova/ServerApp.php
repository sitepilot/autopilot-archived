<?php

namespace App\Nova;

use App\Nova\Actions\AppCertProvisionAction;
use App\Nova\ServerUser;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Actions\AppDestroyAction;
use App\Nova\Actions\AppProvisionAction;
use App\Rules\AppConfigRule;

class ServerApp extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\ServerApp';

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
        return 'Apps';
    }

    /**
     * Returns the menu position.
     *
     * @return int
     */
    public static function menuPosition()
    {
        return 40;
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
                ->help("If the name is left blank Autopilot will generate a random name.")
                ->rules(['min:3', 'alpha_dash', 'unique:server_apps,name,{{resourceId}}', 'nullable'])
                ->readonly(function ($request) {
                    return $request->isUpdateOrUpdateAttachedRequest();
                }),

            BelongsTo::make('User', 'user', ServerUser::class)
                ->searchable()
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

            Text::make('Domain', 'domain')
                ->readonly()
                ->exceptOnForms()
                ->resolveUsing(function ($domain) {
                    return "<a href='https://$domain' target='_blank' class='no-underline dim text-primary font-bold'/>$domain</a>";
                })->asHtml(),

            Text::make('Description', 'description')
                ->hideFromIndex()
                ->sortable(),

            Select::make('State')->options(
                \App\ServerApp::getStates()
            )
                ->exceptOnForms()
                ->displayUsingLabels(),

            Code::make('App Configuration', 'vars')
                ->rules(['required', new AppConfigRule])
                ->json()
                ->onlyOnForms()
                ->hideWhenCreating(),

            Code::make('Default Configuration', 'default_vars')
                ->readonly()
                ->json()
                ->onlyOnForms()
                ->hideWhenCreating(),

            Code::make('App Configuration', 'secure_vars')
                ->readonly()
                ->json()
                ->onlyOnDetail(),

            HasMany::make('Databases', 'databases', ServerDatabase::class),
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
            new AppProvisionAction,
            new AppCertProvisionAction,
            (new AppDestroyAction)->onlyOnDetail()
        ];
    }
}
