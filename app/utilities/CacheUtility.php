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
        $user = \Auth::hasUser() ;
        
        return \Cache::rememberForever($key.'-'.$viewmode.'-'.$user, $closure);
    }
    
    static function forget($key)
    {
        \Cache::forget($key);
    }
    
    static function forgetMonthTable(ClubEvent $event)
    {
        self::forget('monthtable-'. (new \DateTime( $event->evnt_date_start))->format('Y-m'));
    }
    
    static function clear()
    {
        \Artisan::call('cache:clear');
    }
}
