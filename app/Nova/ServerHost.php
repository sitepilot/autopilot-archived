<?php

namespace App\Nova;

use App\Nova\ServerGroup;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\BelongsToMany;

class ServerHost extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\ServerHost';

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
        'name', 'refid', 'description'
    ];

    /**
     * Returns the menu label.
     *
     * @return string
     */
    public static function label()
    {
        return 'Hosts';
    }

    /**
     * Returns the menu position.
     *
     * @return int
     */
    public static function menuPosition()
    {
        return 20;
    }

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string|null
     */
    public function subtitle()
    {
        return $this->refid;
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
                ->help("If the name is left blank Autopilot will assign the refference ID.")
                ->rules(['min:3', 'unique:server_hosts,name,{{resourceId}}', 'nullable']),

            Text::make('Refference', 'refid')
                ->sortable()
                ->exceptOnForms(),

            BelongsTo::make('Group', 'group', ServerGroup::class)
                ->searchable()
                ->sortable()
                ->readonly(function ($request) {
                    return $request->isUpdateOrUpdateAttachedRequest();
                }),

            BelongsTo::make('Client', 'client', Client::class)
                ->searchable()
                ->sortable()
                ->nullable(),

            Text::make('Description', 'description')
                ->sortable()
                ->hideFromIndex(),

            Code::make('Host Configuration', 'vars')
                ->rules(['required', 'json'])
                ->json()
                ->hideWhenCreating(),

            Code::make('Default Configuration', 'default_vars')
                ->readonly()
                ->json()
                ->onlyOnForms()
                ->hideWhenCreating(),

            HasMany::make('Users', 'users', ServerUser::class),

            BelongsToMany::make('Firewall Rules', 'firewallRules', ServerFirewallRule::class)
                ->searchable(),

            MorphToMany::make('Auth Keys', 'authKeys', ServerAuthKey::class)
                ->searchable()
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
