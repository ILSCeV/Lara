<?php

namespace Lara\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Lara\Role;
use Lara\User;
use Lara\utilities\RoleUtility;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can assign the role to a user.
     *
     * @param  \Lara\User $user
     * @param  \Lara\Role $role
     * @return boolean
     */
    public function assign(User $user, Role $role)
    {
        return $user->hasPermission($role) || $user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR);
    }

    /**
     * Determine whether the user can remove the role from a user.
     *
     * @param  \Lara\User $user
     * @param  \Lara\Role $role
     * @return boolean
     */
    public function remove(User $user, Role $role)
    {
        return $user->hasPermission($role) || $user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR);
    }
}
