<?php

namespace Lara\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Lara\User;
use Lara\utilities\RoleUtility;

class UserPolicy
{
    use HandlesAuthorization;


    /**
     * Ensures that the $user variables in the policies methods
     * are of group 'clubleitung'. Admins are allowed to do everything.
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
    {
        /** @var User $user */
        return $user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR, RoleUtility::PRIVILEGE_CL);

    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Lara\User $user
     * @param  \Lara\User $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR) || $user->section_id == $model->section_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Lara\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR, RoleUtility::PRIVILEGE_CL);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Lara\User $user
     * @param  \Lara\User $model
     * @return boolean
     */
    public function update(User $user, User $model)
    {
        return $user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR, RoleUtility::PRIVILEGE_CL);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Lara\User $user
     * @param  \Lara\User $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return $user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR) || $user->section_id == $model->section_id;
    }
}
