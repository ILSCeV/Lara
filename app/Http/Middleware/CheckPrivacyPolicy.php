<?php

namespace Lara\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPrivacyPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->isMethod('get') and ! ($request->is('privacy') or $request->is('lang*'))) {
            if (Auth::check()) {
                if (Auth::user()->privacy_accepted == 0) {
                    return redirect("/privacy");
                }
            }
        }
        return $next($request);
    }
}
