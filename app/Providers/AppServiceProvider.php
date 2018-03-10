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

        Blade::if('is', function($groups) {
            $user = Auth::user();
            if (!$user) {
                return false;
            }

            return $user->is($groups);
        });

        Blade::if('admin', function() {
            $user = Auth::user();

            if (!$user) {
                return false;
            }

            return $user->is("admin");
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
