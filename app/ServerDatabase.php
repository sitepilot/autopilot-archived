<?php

namespace App;

use App\ServerUser;
use App\Traits\HasVars;
use App\Traits\UniqueName;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class ServerDatabase extends Model
{
    use HasVars;
    use UniqueName;

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
        if (isset($this->app->user->id)) {
            $this->user_id = $this->app->user->id;
        }

        $name = '';
        while ($this->nameIsUsed($name)) {
            $name =  $this->user->name . '_db' . ucfirst(Str::random('4'));
        }

        return [
            'name' => $name,
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
}
