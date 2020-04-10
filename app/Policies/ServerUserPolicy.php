<?php

namespace App\Policies;

use App\User;
use App\ServerUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServerUserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the user.
     *
     * @param  User  $user
     * @param  ServerUser  $key
     * @return mixed
     */
    public function view(User $user, ServerUser $key)
    {
        return true;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  User  $user
     * @param  ServerUser  $key
     * @return mixed
     */
    public function update(User $user, ServerUser $key)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  User  $user
     * @param  ServerUser  $key
     * @return mixed
     */
    public function delete(User $user, ServerUser $key)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the user.
     *
     * @param  User  $user
     * @param  ServerUser  $key
     * @return mixed
     */
    public function restore(User $user, ServerUser $key)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the user.
     *
     * @param  User  $user
     * @param  ServerUser  $key
     * @return mixed
     */
    public function forceDelete(User $user, ServerUser $key)
    {
        return false;
    }
}
