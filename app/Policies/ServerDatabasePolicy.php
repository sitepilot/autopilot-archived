<?php

namespace App\Policies;

use App\User;
use App\ServerDatabase;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServerDatabasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any databases.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the database.
     *
     * @param  User  $user
     * @param  ServerDatabase  $key
     * @return mixed
     */
    public function view(User $user, ServerDatabase $key)
    {
        return true;
    }

    /**
     * Determine whether the user can create databases.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the database.
     *
     * @param  User  $user
     * @param  ServerDatabase  $key
     * @return mixed
     */
    public function update(User $user, ServerDatabase $key)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the database.
     *
     * @param  User  $user
     * @param  ServerDatabase  $key
     * @return mixed
     */
    public function delete(User $user, ServerDatabase $key)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the database.
     *
     * @param  User  $user
     * @param  ServerDatabase  $key
     * @return mixed
     */
    public function restore(User $user, ServerDatabase $key)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the database.
     *
     * @param  User  $user
     * @param  ServerDatabase  $key
     * @return mixed
     */
    public function forceDelete(User $user, ServerDatabase $key)
    {
        return false;
    }
}
