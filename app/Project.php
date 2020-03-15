<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    /**
     * Returns the project client.
     *
     * @return BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Returns the project hours.
     *
     * @return HasMany
     */
    public function projectHours()
    {
        return $this->hasMany(ProjectHour::class, 'project_id');
    }

    /**
     * Format offer.
     *
     * @return float
     */
    public function getOfferAttribute($value)
    {
        if (is_null($value)) {
            return 0.00;
        } else {
            return $value;
        }
    }

    /**
     * Amount invoiced.
     *
     * @return float
     */
    public function getInvoicedAttribute($value)
    {
        if (is_null($value)) {
            return 0.00;
        } else {
            return $value;
        }
    }

    /**
     * Format hourly rate.
     *
     * @return float
     */
    public function getHourlyRateAttribute($value)
    {
        if (is_null($value)) {
            return 0.00;
        } else {
            return $value;
        }
    }

    /**
     * Returns the project balance (offer - invoiced).
     *
     * @return float
     */
    public function getBalanceAttribute()
    {
        return $this->offer - $this->invoiced;
    }

    /**
     * Returns the remaining hours.
     *
     * @return float
     */
    public function getRemainingHoursAttribute()
    {
        if ($this->offer > 0 && $this->hourly_rate > 0 && $this->state == 'in-progress') {
            return round($this->offer / $this->hourly_rate - $this->projectHours->where('billable', false)->sum('hours'), 1);
        }

        return null;
    }
}
