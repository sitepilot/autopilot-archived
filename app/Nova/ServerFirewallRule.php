<?php

namespace App\Nova;

use App\Nova\ServerHost;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;

class ServerFirewallRule extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\ServerFirewallRule';

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
        'name',
    ];

    /**
     * Returns the menu label.
     *
     * @return string
     */
    public static function label()
    {
        return 'Firewall Rules';
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
            ID::make()->sortable(),

            Text::make('Name', 'name')
                ->sortable()
                ->readonly()
                ->hideWhenCreating(),

            Text::make('Name', 'name')
                ->sortable()
                ->onlyOnForms()
                ->hideWhenUpdating(),

            Code::make('Rule Configuration', 'vars')
                ->rules(['required', 'json'])
                ->json()
                ->hideWhenCreating(),

            Code::make('Default Configuration', 'default_vars')
                ->readonly()
                ->json()
                ->onlyOnForms()
                ->hideWhenCreating(),

            HasMany::make('Hosts', 'hosts', ServerHost::class),
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
        return [];
    }
}
