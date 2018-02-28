<?php

namespace Lara\Providers;

use Illuminate\Support\Facades\Gate;
use Lara\User;
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
    }
}
