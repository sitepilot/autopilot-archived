<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Markdown;

class Client extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Client';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Admin';

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
        'name', 'refid', 'notes'
    ];

    /**
     * Returns the menu label.
     *
     * @return string
     */
    public static function label()
    {
        return 'Clients';
    }

    /**
     * Returns the menu position.
     *
     * @return int
     */
    public static function menuPosition()
    {
        return 10;
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
                ->rules(['required', 'min:3', 'unique:clients,name,{{resourceId}}']),

            Text::make('Refference', 'refid')
                ->sortable()
                ->readonly()
                ->hideWhenCreating(),

            Markdown::make('Notes', 'notes')
                ->sortable(),

            Text::make('Projects', 'projects')
                ->exceptOnForms()
                ->resolveUsing(function ($projects) {
                    if ($projects->count()) {
                        $count = $projects->count();
                        return $projects->count() . ' ' . ($count == 1 ? 'project' : 'projects');
                    }
                    return null;
                }),

            Text::make('Users', 'serverUsers')
                ->exceptOnForms()
                ->resolveUsing(function ($users) {
                    if ($users->count()) {
                        $count = $users->count();
                        return $users->count() . ' ' . ($count == 1 ? 'user' : 'users');
                    }
                    return null;
                }),

            Text::make('Apps', 'serverApps')
                ->exceptOnForms()
                ->resolveUsing(function ($apps) {
                    if ($apps->count()) {
                        $count = $apps->count();
                        return $apps->count() . ' ' . ($count == 1 ? 'app' : 'apps');
                    }
                    return null;
                }),

            Text::make('Databases', 'serverDatabases')
                ->exceptOnForms()
                ->resolveUsing(function ($databases) {
                    if ($databases->count()) {
                        $count = $databases->count();
                        return $databases->count() . ' ' . ($count == 1 ? 'database' : 'databases');
                    }
                    return null;
                }),

            HasMany::make('Projects', 'projects', Project::class),

            HasMany::make('Time Registrations', 'projectHours', ProjectHour::class),

            HasMany::make('Server Users', 'serverUsers', ServerUser::class),

            HasMany::make('Server Hosts', 'serverHosts', ServerHost::class),

            HasMany::make('Server Apps', 'serverApps', ServerApp::class),

            HasMany::make('Server Databases', 'serverDatabases', ServerDatabase::class),
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
