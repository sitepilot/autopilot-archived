<?php

namespace App\Policies;

use App\User;
use App\Client;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any clients.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the client.
     *
     * @param  User  $user
     * @param  Client  $client
     * @return mixed
     */
    public function view(User $user, Client $client)
    {
        return true;
    }

    /**
     * Determine whether the user can create clients.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the client.
     *
     * @param  User  $user
     * @param  Client  $client
     * @return mixed
     */
    public function update(User $user, Client $client)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the client.
     *
     * @param  User  $user
     * @param  Client  $client
     * @return mixed
     */
    public function delete(User $user, Client $client)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the client.
     *
     * @param  User  $user
     * @param  Client  $client
     * @return mixed
     */
    public function restore(User $user, Client $client)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the client.
     *
     * @param  User  $user
     * @param  Client  $client
     * @return mixed
     */
    public function forceDelete(User $user, Client $client)
    {
        return true;
    }

    /**
     * Determine whether the user can add a time registration.
     *
     * @param  User  $user
     * @param  Client  $client
     * @return mixed
     */
    public function addProjectHour(User $user, Client $client)
    {
        return false;
    }

    /**
     * Determine whether the user can add a server app.
     *
     * @param  User  $user
     * @param  Client  $client
     * @return mixed
     */
    public function addServerApp(User $user, Client $client)
    {
        return false;
    }

    /**
     * Determine whether the user can add a server database.
     *
     * @param  User  $user
     * @param  Client  $client
     * @return mixed
     */
    public function addServerDatabase(User $user, Client $client)
    {
        return false;
    }
}
