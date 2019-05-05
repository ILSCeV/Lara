<?php
/**
 * Utility Class to provide cache functions
 *
 * User: fabian
 * Date: 31.12.18
 * Time: 02:24
 */

namespace Lara\utilities;


use Lara\ClubEvent;
use Lara\User;
use Lara\Survey;

class CacheUtility
{
    /**
     * @param $key
     * @param \Closure $closure
     * @return mixed
     */
    static function remember($key, \Closure $closure)
    {
        $viewmode = \Session::get('view_mode', 'light');
        $user = \Auth::hasUser();
        
        return \Cache::rememberForever($key.'-'.$viewmode.'-'.$user, $closure);
    }
    
    static function forget($key)
    {
        $viewModes = collect(['dark', 'light']);
        $hasUsers = collect([true, false]);
        $viewModes->each(function ($viewmode) use ($hasUsers, $key) {
            $hasUsers->each(function ($user) use ($viewmode, $key) {
                \Cache::forget($key.'-'.$viewmode.'-'.$user);
            });
        });
    }
    
    static function forgetMonthTable(ClubEvent $event)
    {
        $clUsers = User::query()->whereHas('roles', function (\Illuminate\Database\Eloquent\Builder $query){
            $query->where('name','=',RoleUtility::PRIVILEGE_CL);
        })->get();
        
        $datePattern = (new \DateTime($event->evnt_date_start))->format('Y-m');
        self::forget('monthtable-'.$datePattern);
        self::forget('monthtable-admin-'.$datePattern);
        $clUsers->map(function (User $user) use ($datePattern) {
           self::forget('monthtable-cl-'.$user->id.'-'-$datePattern);
        });
    }
    
static function forgetMonthTableSurvey(Survey $survey)
    {
        self::forget('monthtable-'.(new \DateTime($survey->deadline))->format('Y-m'));
    }

    static function clear()
    {
        \Artisan::call('cache:clear');
    }
}
