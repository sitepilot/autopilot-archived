<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function (Client $item) {
            $statement = DB::select("SHOW TABLE STATUS LIKE '" . $item->getTable() . "'");
            $nextId = $statement[0]->Auto_increment;
            $item->refid = "deb" . $nextId;
        });
    }

    /**
     * Returns the client projects.
     *
     * @return HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    /**
     * Returns the client server users.
     *
     * @return HasMany
     */
    public function serverUsers()
    {
        return $this->hasMany(ServerUser::class, 'client_id');
    }

    /**
     * Returns the client server apps.
     *
     * @return HasManyThrough
     */
    public function serverApps()
    {
        return $this->hasManyThrough(ServerApp::class, ServerUser::class, 'client_id', 'user_id', 'id');
    }

    /**
     * Returns the client server databases.
     *
     * @return HasManyThrough
     */
    public function serverDatabases()
    {
        return $this->hasManyThrough(ServerDatabase::class, ServerUser::class, 'client_id', 'user_id', 'id');
    }

    /**
     * Returns the client server hosts.
     *
     * @return HasMany
     */
    public function serverHosts()
    {
        return $this->hasMany(ServerHost::class, 'client_id');
    }

    /**
     * Returns the client project hours.
     *
     * @return HasManyThrough
     */
    public function projectHours()
    {
        return $this->hasManyThrough(ProjectHour::class, Project::class, 'client_id', 'project_id', 'id');
    }
}
