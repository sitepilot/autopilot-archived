<?php

namespace App;

use App\ServerUser;
use App\Traits\HasVars;
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
            'name' => $this->refid,
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

    /**
     * Returns the app database.
     *
     * @return void
     */
    public function app()
    {
        return $this->belongsTo(ServerApp::class, 'app_id');
    }

    /**
     * Returns the client.
     *
     * @return void
     */
    public function client()
    {
        return $this->hasOneThrough(Client::class, ServerUser::class, 'client_id', 'id', 'user_id');
    }
}
