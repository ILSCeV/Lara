<?php

namespace Lara\Policies;

use Lara\User;
use Lara\ClubEvent;
use Lara\utilities\RoleUtility;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ClubEventPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks. Admins can do anything
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the clubEvent.
     *
     * @param  \Lara\User  $user
     * @param  \Lara\ClubEvent  $event
     * @return bool
     */
    public function view(?User $user, ClubEvent $event)
    {
        if (!$user && $event->evnt_is_private == 1) {
            return false;
        }
        return true;
    }

    /**
     * Determine whether the user can create clubEvents.
     * Any logged in user can create an event
     *
     * @param  \Lara\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the clubEvent.
     * The creator, CL & marketing belonging to that section can update the event.
     *
     * @param  \Lara\User  $user
     * @param  \Lara\ClubEvent  $event
     * @return \Illuminate\Auth\Access\Response
     */
    public function update(User $user, ClubEvent $event)
    {
        if ($user->is($event->creator)) {
            return Response::allow();
        }

        if ($user->hasPermissionsInSection($event->section, RoleUtility::PRIVILEGE_CL, RoleUtility::PRIVILEGE_MARKETING)) {
            return Response::allow();
        }

        return Response::deny(__('mainlang.cantUpdateEvent'));
    }

    /**
     * Determine whether the user can delete the clubEvent.
     * The creator as long as the event is private, CL & marketing belonging to that section can delete the event.
     * @param  \Lara\User  $user
     * @param  \Lara\ClubEvent  $event
     * @return \Illuminate\Auth\Access\Response
     */
    public function delete(User $user, ClubEvent $event)
    {
        if ($user->is($event->creator) && $event->evnt_is_private) {
            return Response::allow();
        }

        if ($user->hasPermissionsInSection($event->section, RoleUtility::PRIVILEGE_CL, RoleUtility::PRIVILEGE_MARKETING)) {
            return Response::allow();
        }

        return Response::deny(__('mainLang.cantDeleteEvent'));
    }
}
