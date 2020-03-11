<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Nova\Filters\DateFilter as Datefilter;

class DateEndFilter extends Datefilter
{
    /**
     * The displayable name of the filter.
     *
     * @var string
     */
    public $name = 'End Date';

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
        $value = Carbon::parse($value);

        return $query->where('created_at', '<=', Carbon::parse($value));
    }
}
