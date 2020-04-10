<?php

namespace App\Policies;

use App\User;
use App\ServerApp;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServerAppPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any apps.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the app.
     *
     * @param  User  $user
     * @param  ServerApp  $key
     * @return mixed
     */
    public function view(User $user, ServerApp $key)
    {
        return true;
    }

    /**
     * Determine whether the user can create apps.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the app.
     *
     * @param  User  $user
     * @param  ServerApp  $key
     * @return mixed
     */
    public function update(User $user, ServerApp $key)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the app.
     *
     * @param  User  $user
     * @param  ServerApp  $key
     * @return mixed
     */
    public function delete(User $user, ServerApp $key)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the app.
     *
     * @param  User  $user
     * @param  ServerApp  $key
     * @return mixed
     */
    public function restore(User $user, ServerApp $key)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the app.
     *
     * @param  User  $user
     * @param  ServerApp  $key
     * @return mixed
     */
    public function forceDelete(User $user, ServerApp $key)
    {
        return false;
    }
}
