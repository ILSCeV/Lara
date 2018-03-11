<?php

namespace Lara\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
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
        'Lara\User' => 'Lara\Policies\UserPolicy'
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
            if ($user->is(RoleUtility::PRIVILEGE_ADMINISTRATOR)) {
                return true;
            }
            return $user->hasPermissionsInSection(Section::findOrFail($section_id),RoleUtility::PRIVILEGE_CL);
        });

    }
}
