<?php

namespace App;

use App\ServerUser;
use App\Traits\HasVars;
use Illuminate\Database\Eloquent\Model;

class ServerApp extends Model
{
    use HasVars;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'vars' => 'array',
    ];

    /**
     * Returns an array with default app variables.
     *
     * @return void
     */
    public function getDefaultVars()
    {
        $faker = \Faker\Factory::create('en_GB');
        $name = 'app-' . $faker->domainWord;

        return [
            'name' => $name,
            'domain' => $name . '.' . env('APP_DEFAULT_DOMAIN'),
            'aliases' => []
        ];
    }

    /**
     * Returns the app user.
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(ServerUser::class, 'user_id');
    }

    /**
     * Returns the domain variable.
     *
     * @return void
     */
    public function getDomainAttribute()
    {
        return $this->getVar('domain');
    }

    /**
     * Set the domain variable.
     *
     * @return void
     */
    public function setDomainAttribute($value)
    {
        $this->setVar('domain', $value, true, true);
    }
}
