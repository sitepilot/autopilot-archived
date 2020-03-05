<?php

namespace App;

use App\ServerUser;
use App\Traits\HasVars;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class ServerDatabase extends Model
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
        return [
            'name' => $this->user->name . '_' . Str::random(6),
            'state' => 'present'
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
}
