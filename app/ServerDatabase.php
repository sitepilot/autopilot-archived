<?php

namespace App;

use App\Client;
use App\ServerUser;
use App\Traits\HasVars;
use App\Traits\HasState;
use App\Traits\Encryptable;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;

class ServerDatabase extends Model
{
    use HasVars;
    use HasState;
    use Actionable;
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
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function (ServerDatabase $item) {
            if (isset($item->app->user_id)) {
                $item->user_id = $item->app->user_id;
            }

            $unique = true;
            while ($unique) {
                $name = $item->user->name . '_db' . ucfirst(Str::random(4));
                $unique = $item->where('name', $name)->count();
            }
            $item->name = $name;
        });
    }

    /**
     * Returns an array with default app variables.
     *
     * @return void
     */
    public function getDefaultVars()
    {
        return [
            'name' => $this->name,
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
     * @return Client
     */
    public function getClientAttribute()
    {
        if ($this->user) {
            return $this->user->client;
        }

        return null;
    }
}
