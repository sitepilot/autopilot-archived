<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class BillableFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'Billable';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        switch ($value) {
            case 'billable':
                return $query->where('billable', true)
                    ->where('invoiced', false);
            case 'invoiced':
                return $query->where('billable', true)
                    ->where('invoiced', true);
            case 'non-billable':
                return $query->where('billable', false)
                    ->where('invoiced', false);
            default:
                return $query;
        }
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            'Billable' => 'billable',
            'Invoiced' => 'invoiced',
            'Non Billable' => 'non-billable'
        ];
    }
}
