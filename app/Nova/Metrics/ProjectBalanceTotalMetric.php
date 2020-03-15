<?php

namespace App\Nova\Metrics;

use App\Project;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Http\Requests\NovaRequest;

class ProjectBalanceTotalMetric extends Value
{
    /**
     * The displayable name of the metric.
     *
     * @var string
     */
    public $name = 'Project Balance';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $totalOffer = $this->sum($request, Project::whereIn('state', ['100-in-progress', '300-done']), 'offer');
        $totalInvoiced = $this->sum($request, Project::whereIn('state', ['100-in-progress', '300-done']), 'invoiced');

        return $this->result($totalOffer->value - $totalInvoiced->value)->previous($totalOffer->previous - $totalInvoiced->previous)->prefix('€');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'YTD' => 'Year To Date',
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            30 => '30 Days',
            60 => '60 Days',
            365 => '365 Days',
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'project-budget-total';
    }
}
