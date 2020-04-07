<?php

namespace App;

use App\Traits\HasVars;
use App\Traits\Encryptable;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\MorphedByMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServerAuthKey extends Model
{
    use HasVars;
    use SoftDeletes;
    use Encryptable;

    /**
     * The attributes that should be encrypted.
     *
     * @var array
     */
    protected $encryptable = [
        'vars',
    ];

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
            'name' => Str::slug($this->name),
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
