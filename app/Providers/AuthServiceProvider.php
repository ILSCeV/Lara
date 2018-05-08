<?php

namespace Lara\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Lara\Policies\RolePolicy;
use Lara\Policies\UserPolicy;
use Lara\Role;
use Lara\Section;
use Lara\User;
use Lara\utilities\RoleUtility;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'Lara\Model' => 'Lara\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('createUserOfSection', function (User $user, $section_id) {
            if ($user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR)) {
                return true;
            }
            return $user->hasPermissionsInSection(Section::findOrFail($section_id),RoleUtility::PRIVILEGE_CL);
        });

        Gate::define('accessInformation', function(User $user, User $otherUser) {
            if (!$user) {
                return false;
            }

            if ($user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR)) {
                return true;
            }

            $isClInSameSection = $user->hasPermissionsInSection($otherUser->section, RoleUtility::PRIVILEGE_CL);

            if($isClInSameSection) {
                return true;
            }

            $isSameSection = $user->section->id == $otherUser->section->id;

            return $isSameSection;
        });


    }
}
