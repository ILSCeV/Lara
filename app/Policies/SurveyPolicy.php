<?php

namespace Lara\Policies;

use Lara\User;
use Lara\Survey;

use Lara\utilities\RoleUtility;
use Illuminate\Auth\Access\HandlesAuthorization;

class SurveyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the survey.
     *
     * @param  \Lara\User  $user
     * @param  \Lara\Survey  $survey
     * @return mixed
     */
    public function view(User $user, Survey $survey)
    {

    }

    /**
     * Determine whether the user can create surveys.
     *
     * @param  \Lara\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the survey.
     *
     * @param  \Lara\User  $user
     * @param  \Lara\Survey  $survey
     * @return mixed
     */
    public function update(User $user, Survey $survey)
    {
        if ($user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR)) {
            return true;
        }

        if ($survey->creator!=null && $user->is($survey->creator->user)) {
            return true;
        }

        $isClOrMarketing = $user->hasPermissionsInSection($survey->section(), RoleUtility::PRIVILEGE_CL, RoleUtility::PRIVILEGE_MARKETING);

        if ($isClOrMarketing) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the survey.
     *
     * @param  \Lara\User  $user
     * @param  \Lara\Survey  $survey
     * @return mixed
     */
    public function delete(User $user, Survey $survey)
    {
        return $this->update($user, $survey);
    }
}
