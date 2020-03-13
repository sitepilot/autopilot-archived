<?php

namespace App\Policies;

use App\User;
use App\ServerAuthKey;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServerAuthKeyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any auth keys.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the auth key.
     *
     * @param  \App\User  $user
     * @param  \App\ServerAuthKey  $key
     * @return mixed
     */
    public function view(User $user, ServerAuthKey $key)
    {
        return true;
    }

    /**
     * Determine whether the user can create auth keys.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the auth key.
     *
     * @param  \App\User  $user
     * @param  \App\ServerAuthKey  $key
     * @return mixed
     */
    public function update(User $user, ServerAuthKey $key)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the auth key.
     *
     * @param  \App\User  $user
     * @param  \App\ServerAuthKey  $key
     * @return mixed
     */
    public function delete(User $user, ServerAuthKey $key)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the auth key.
     *
     * @param  \App\User  $user
     * @param  \App\ServerAuthKey  $key
     * @return mixed
     */
    public function restore(User $user, ServerAuthKey $key)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the auth key.
     *
     * @param  \App\User  $user
     * @param  \App\ServerAuthKey  $key
     * @return mixed
     */
    public function forceDelete(User $user, ServerAuthKey $key)
    {
        return true;
    }

    /**
     * Determine whether the user can add a server host.
     *
     * @param  User  $user
     * @param  ServerAuthKey  $key
     * @return mixed
     */
    public function addServerHost(User $user, ServerAuthKey $key)
    {
        return false;
    }

    /**
     * Determine whether the user can add a server user.
     *
     * @param  User  $user
     * @param  ServerAuthKey  $key
     * @return mixed
     */
    public function addServerUser(User $user, ServerAuthKey $key)
    {
        return false;
    }
}
