<?php

namespace App\Nova;

use App\Nova\ServerHost;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;
use App\Rules\ServerFirewallConfigRule;

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
        'name', 'description'
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
                ->rules(['required', 'alpha_dash', 'min:3', 'unique:server_firewall_rules,name,{{resourceId}}'])
                ->readonly(function ($request) {
                    return $request->isUpdateOrUpdateAttachedRequest();
                }),

            Number::make('Port', 'port')
                ->hideWhenCreating()
                ->readonly(function ($request) {
                    return $request->isUpdateOrUpdateAttachedRequest();
                }),

            Text::make('Description', 'description')
                ->hideFromIndex()
                ->sortable(),

            Code::make('Rule Configuration', 'vars')
                ->rules(['required', new ServerFirewallConfigRule])
                ->json()
                ->onlyOnForms()
                ->hideWhenCreating(),

            Code::make('Default Configuration', 'default_vars')
                ->readonly()
                ->json()
                ->onlyOnForms()
                ->hideWhenCreating(),

            Code::make('Rule Configuration', 'secure_vars')
                ->readonly()
                ->json()
                ->onlyOnDetail(),

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
