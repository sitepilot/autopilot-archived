<?php

namespace App;

use App\Traits\HasVars;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class ServerUser extends Model
{
    use HasVars;
    use SoftDeletes;
    use Actionable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'vars' => 'array',
    ];

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function (ServerUser $item) {
            $statement = DB::select("SHOW TABLE STATUS LIKE '" . $item->getTable() . "'");
            $nextId = $statement[0]->Auto_increment;
            $item->name = "user" . $nextId;
        });
    }

    /**
     * Returns an array with default user variables.
     *
     * @return void
     */
    public function getDefaultVars()
    {
        return [
            'name' => $this->name,
            'full_name' => $this->name,
            'isolated' => true,
            'password' => Str::random(12),
            'mysql_password' => Str::random(12),
            'apps' => [],
            'databases' => [],
            'auth_keys' => []
        ];
    }

    /**
     * Returns the user host.
     *
     * @return BelongsTo
     */
    public function host()
    {
        return $this->belongsTo(ServerHost::class, 'host_id');
    }

    /**
     * Returns the client.
     *
     * @return BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Returns the user apps.
     *
     * @return HasMany
     */
    public function apps()
    {
        return $this->hasMany(ServerApp::class, 'user_id');
    }

    /**
     * Returns the user databases.
     *
     * @return HasMany
     */
    public function databases()
    {
        return $this->hasMany(ServerDatabase::class, 'user_id');
    }

    /**
     * Returns the user auth keys.
     *
     * @return MorphToMany
     */
    public function authKeys()
    {
        return $this->morphToMany(ServerAuthKey::class, 'keyable', 'server_auth_keyables', 'keyable_id', 'key_id');
    }

    /**
     * Returns the isolated var.
     *
     * @return boolean
     */
    public function getIsolatedAttribute()
    {
        return $this->getVar('isolated');
    }

    /**
     * Set the isolated var.
     *
     * @param boolean $value
     * @return void
     */
    public function setIsolatedAttribute($value)
    {
        $this->setVar('isolated', $value, true, true);
    }
}
