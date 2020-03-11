<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
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
     * Returns the client server hosts.
     *
     * @return HasMany
     */
    public function serverHosts()
    {
        return $this->hasMany(ServerHost::class, 'client_id');
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
}
