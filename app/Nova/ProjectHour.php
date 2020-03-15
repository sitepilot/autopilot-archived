<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasOne;

class ProjectHour extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\ProjectHour';

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
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title', 'notes'
    ];

    /**
     * The default sort order.
     *
     * @var array
     */
    public static $orderBy = ['created_at' => 'desc'];

    /**
     * Returns the menu label.
     *
     * @return string
     */
    public static function label()
    {
        return 'Hours';
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
     * Returns the singular label.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return 'Registration';
    }

    /**
     * Get the search result subtitle for the resource.
     *
     * @return string|null
     */
    public function subtitle()
    {
        return $this->type;
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
            Date::make('Date', 'created_at')
                ->rules(['required', 'date'])
                ->sortable(),

            Text::make('Title', 'title')
                ->sortable()
                ->rules(['required', 'min:3']),

            BelongsTo::make('User', 'user', User::class)
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            BelongsTo::make('Project', 'project', Project::class)
                ->sortable()
                ->searchable(),

            Text::make('Client', 'client')
                ->exceptOnForms()
                ->resolveUsing(function ($client) {
                    if (isset($client->name)) {
                        return "<a href='" . url(config("nova.path") . "/resources/clients/" . $client->id) . "' 
                            class='no-underline dim text-primary font-bold'>" . $client->name . "</a>";
                    }
                    return null;
                })->asHtml(),

            Markdown::make('Notes', 'notes')
                ->alwaysShow(),

            Select::make('Type', 'type')->options([
                'admin' => 'Administration',
                'development' => 'Development',
                'support' => 'Support',
                'general' => 'General'
            ])
                ->sortable()
                ->rules(['required'])
                ->displayUsingLabels(),

            Number::make('Hours', 'hours')
                ->min(0)->step(0.01)
                ->rules(['required_without:minutes', 'numeric', 'nullable']),

            Number::make('Time in minutes', 'minutes')
                ->min(0)->step(1)
                ->rules(['required_without:hours', 'numeric', 'nullable'])
                ->help("This field will be converted to hours on save.")
                ->onlyOnForms(),

            Boolean::make('Billable', 'billable')
                ->sortable(),

            Boolean::make('Invoiced', 'invoiced')
                ->sortable()
                ->hideWhenCreating()
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
        return [
            (new Metrics\ProjectHoursTotalMetric)
                ->help('The sum of all project hours.'),
            (new Metrics\ProjectHoursBillableMetric)
                ->help('The sum of all project hours which are billable.'),
            (new Metrics\ProjectHoursNonBillableMetric)
                ->help('The sum of all project hours which are not billable.')
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new Filters\DateStartFilter,
            new Filters\DateEndFilter,
            new Filters\BillableFilter,
        ];
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
