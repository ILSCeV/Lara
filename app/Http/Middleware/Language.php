<?php

namespace Lara\Http\Middleware;

use App;
use Application;
use Closure;
use Config;
use Log;
use Redirector;
use Session;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Session::has('applocale') AND array_key_exists(Session::get('applocale'), Config::get('languages'))) {
            App::setLocale(Session::get('applocale'));
        } else {
            //Detect Browser Prefered language
            $locale = substr($request->server('HTTP_ACCEPT_LANGUAGE', "de"), 0, 2);
            if (isset($locale) && array_key_exists($locale, Config::get('languages'))) {
                App::setLocale($locale);
                Session::put('applocale', $locale);
            } else {
                //Optional as Laravel will automatically set the fallback language if there is none specified
                App::setLocale(Config::get('app.locale'));
            }
        }
        
        return $next($request);
    }
}
