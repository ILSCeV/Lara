<?php

namespace Lara\Providers;

use Auth;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Lara\ClubEvent;
use Lara\Observers\ClubEventObserver;
use Lara\Observers\ScheduleObserver;
use Lara\Observers\ShiftObserver;
use Lara\Observers\SurveyObserver;
use Lara\Role;
use Lara\Schedule;
use Lara\Section;
use Lara\Shift;
use Lara\Survey;
use Lara\User;
use Lara\utilities\RoleUtility;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Blade::if('dev', function() {
            return App::environment('development');
        });

        Blade::if('berta', function() {
            return App::environment('berta');
        });

        Blade::if('is', function(...$groups) {
            $user = Auth::user();
            if (!$user) {
                return false;
            }

            return $user->isAn(...$groups);
        });

        Blade::if('isInSection', function (array $groups, Section $section) {
            $user = Auth::user();
            if (!$user) {
                return false;
            }
            return $user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR) || $user->hasPermissionsInSection($section, ...$groups);
        });

        Blade::if('admin', function() {
            $user = Auth::user();

            if (!$user) {
                return false;
            }

            return $user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR);
        });

        Blade::if('canEditUser', function (User $editUser) {
            $user = Auth::user();
            if(!$user) {
                return false;
            }

            return $user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR) ||
                ($user->getSectionsIdForRoles(RoleUtility::PRIVILEGE_CL)
                    ->contains($editUser->section_id)
                    && (strpos(env('LDAP_SECTIONS', ''), $editUser->section->title) == false
                    || \App::environment('development'))
                );
        });

        Blade::if('hasRole',function (Role $role){
            $user = Auth::user();
            if(!$user) {
                return false;
            }
            return $user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR) || $user->hasPermission($role);
        });

        Blade::if('noLdapUser', function(){
            $user = Auth::user();
            if(!$user) {
                return false;
            }
          if($user->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR)){
                return true;
          }
          return strpos(env('LDAP_SECTIONS', ''), $user->section->title) == false
              || \App::environment('development');
        });

        Blade::if('ldapSection', function($section) {
            return strpos(env('LDAP_SECTIONS', ''), $section->title) !== false;
        });
    
        Paginator::defaultView('pagination::bootstrap-4');
    
        Paginator::defaultSimpleView('pagination::bootstrap-4');
        
        ClubEvent::observe(ClubEventObserver::class);
        Schedule::observe(ScheduleObserver::class);
        Survey::observe(SurveyObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
