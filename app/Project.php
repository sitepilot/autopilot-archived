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
    public function hours()
    {
        return $this->hasMany(ProjectHour::class, 'project_id');
    }

    /**
     * Format budget.
     *
     * @return float
     */
    public function getBudgetAttribute($value)
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
}
