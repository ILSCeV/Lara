<?php

namespace Lara\Providers;

use Auth;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\App;
use Lara\ClubEvent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('dev', function() {
            return App::environment('development');
        });

        Blade::if('berta', function() {
            return App::environment('berta');
        });

        Blade::if('is', function($permissions) {
            if (!is_array($permissions)) {
                $permissions = [$permissions];
            }

            $user = Auth::user();
            if (!$user) {
                return false;
            }

            return collect($permissions)
                ->contains($user->group);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
