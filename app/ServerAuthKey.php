<?php

namespace App;

use App\Traits\HasVars;
use Laravel\Nova\Fields\MorphedByMany;
use Illuminate\Database\Eloquent\Model;

class ServerAuthKey extends Model
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
     * Returns an array with default auth key variables.
     *
     * @return void
     */
    public function getDefaultVars()
    {
        return [
            'name' => $this->refid,
            'key' => null
        ];
    }

    /**
     * Returns auth key hosts.
     * 
     * @return MorphedByMany
     */
    public function hosts()
    {
        return $this->morphedByMany(ServerHost::class, 'keyable', 'server_auth_keyables', 'key_id', 'keyable_id');
    }

    /**
     * Returns auth key users.
     * 
     * @return MorphedByMany
     */
    public function users()
    {
        return $this->morphedByMany(ServerUser::class, 'keyable', 'server_auth_keyables', 'key_id', 'keyable_id');
    }
}
