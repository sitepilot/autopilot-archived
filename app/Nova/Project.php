<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\BelongsTo;

class Project extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Project';

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
        return 'Projects';
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
                ->rules(['required', 'min:3', 'unique:projects,name,{{resourceId}}']),

            Text::make('Refference', 'refid')
                ->sortable()
                ->hideWhenCreating()
                ->rules(['required', 'unique:projects,refid,{{resourceId}}']),

            BelongsTo::make('Client', 'client', Client::class)
                ->searchable(),

            Currency::make('Budget', 'budget')
                ->sortable()
                ->rules(['numeric', 'nullable']),

            Currency::make('Hourly Rate', 'hourly_rate')
                ->sortable()
                ->rules(['numeric', 'nullable']),

            Markdown::make('Notes', 'notes')->alwaysShow(),

            HasMany::make('Time Registrations', 'projectHours', ProjectHour::class)
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
            (new Metrics\ProjectTotalMetric)
                ->help('The number of projects.'),
            (new Metrics\ProjectBudgetTotalMetric)
                ->help('The sum of all budgets for all projects.'),
            (new Metrics\ProjectBillableHoursMetric)
                ->help('The sum of all project hours which are billable and not invoiced.')
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
