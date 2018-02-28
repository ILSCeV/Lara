<?php

namespace Lara\Providers;

use Illuminate\Support\Facades\Gate;
use Lara\User;
use Lara\Section;
use Lara\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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

        Gate::define('createUserOfSection', function(User $user, $section_id) {
            if ($user->group === 'admin') {
                return true;
            }

            if ($user->group !== 'clubleitung') {
                return false;
            }

            if ($user->section_id != $section_id) {
                return false;
            }

            return true;
        });

    }
}
