<?php

namespace App;

use App\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectHour extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Returns the project client.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Returns the project.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Returns the client.
     *
     * @return Client
     */
    public function getClientAttribute()
    {
        return $this->project->client;
    }

    /**
     * Calculate hours based on minutes.
     *
     * @return float
     */
    public function setMinutesAttribute($value)
    {
        if ($this->attributes['hours'] == $this->getOriginal('hours')) {
            $this->attributes['hours'] = ceil(($value * 100) / 60) / 100;
        }

        unset($this->attributes['minutes']);
    }

    /**
     * Calculate minutes based on hours.
     *
     * @return integer
     */
    public function getMinutesAttribute()
    {
        return isset($this->attributes['hours']) ? round($this->attributes['hours'] * 60) : null;
    }
}
