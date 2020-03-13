<?php

namespace App\Policies;

use App\User;
use App\ServerFirewallRule;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServerFirewallRulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any firewall rules.
     *
     * @param  User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the firewall rule.
     *
     * @param  User  $user
     * @param  ServerFirewallRule  $rule
     * @return mixed
     */
    public function view(User $user, ServerFirewallRule $rule)
    {
        return true;
    }

    /**
     * Determine whether the user can create firewall rules.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the firewall rule.
     *
     * @param  User  $user
     * @param  ServerFirewallRule  $rule
     * @return mixed
     */
    public function update(User $user, ServerFirewallRule $rule)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the firewall rule.
     *
     * @param  User  $user
     * @param  ServerFirewallRule  $rule
     * @return mixed
     */
    public function delete(User $user, ServerFirewallRule $rule)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the firewall rule.
     *
     * @param  User  $user
     * @param  ServerFirewallRule  $rule
     * @return mixed
     */
    public function restore(User $user, ServerFirewallRule $rule)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the firewall rule.
     *
     * @param  User  $user
     * @param  ServerFirewallRule  $rule
     * @return mixed
     */
    public function forceDelete(User $user, ServerFirewallRule $rule)
    {
        return true;
    }

    /**
     * Determine whether the user can add a server host.
     *
     * @param  User  $user
     * @param  ServerFirewallRule  $rule
     * @return mixed
     */
    public function addServerHost(User $user, ServerFirewallRule $rule)
    {
        return false;
    }
}
