<?php

namespace Lara\Policies;

use Lara\User;
use Lara\ClubEvent;
use Lara\utilities\RoleUtility;

use Illuminate\Auth\Access\HandlesAuthorization;

class ClubEventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the clubEvent.
     *
     * @param  \Lara\User  $user
     * @param  \Lara\ClubEvent  $clubEvent
     * @return mixed
     */
    public function view(User $user, ClubEvent $clubEvent)
    {
        return true;
    }

    /**
     * Determine whether the user can create clubEvents.
     *
     * @param  \Lara\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the clubEvent.
     *
     * @param  \Lara\User  $user
     * @param  \Lara\ClubEvent  $clubEvent
     * @return mixed
     */
    public function update(User $user, ClubEvent $clubEvent)
    {
        if ($user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR)) {
            return true;
        }

        if ($user->is($clubEvent->creator)) {
            return true;
        }

        if ($user->hasPermissionsInSection($clubEvent->section, RoleUtility::PRIVILEGE_CL, RoleUtility::PRIVILEGE_MARKETING)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the clubEvent.
     *
     * @param  \Lara\User  $user
     * @param  \Lara\ClubEvent  $clubEvent
     * @return mixed
     */
    public function delete(User $user, ClubEvent $clubEvent)
    {
        //
    }
}
