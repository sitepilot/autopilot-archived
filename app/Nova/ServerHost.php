<?php

namespace App\Nova;

use App\Nova\Actions\ServerCertRenewAction;
use App\Nova\ServerGroup;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphToMany;
use App\Nova\Actions\ServerTestAction;
use Laravel\Nova\Fields\BelongsToMany;
use App\Nova\Actions\ServerProvisionAction;
use App\Rules\ServerHostConfigRule;

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
        'name', 'description'
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
                ->hideFromIndex()
                ->sortable(),

            Select::make('State')->options(
                \App\ServerHost::getStates()
            )
                ->exceptOnForms()
                ->displayUsingLabels(),

            Code::make('Host Configuration', 'vars')
                ->rules(['required', new ServerHostConfigRule])
                ->json()
                ->onlyOnForms()
                ->hideWhenCreating(),

            Code::make('Default Configuration', 'default_vars')
                ->readonly()
                ->json()
                ->onlyOnForms()
                ->hideWhenCreating(),

            Code::make('Host Configuration', 'secure_vars')
                ->readonly()
                ->json()
                ->onlyOnDetail(),

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
        return [
            new ServerTestAction,
            new ServerProvisionAction,
            new ServerCertRenewAction
        ];
    }
}
