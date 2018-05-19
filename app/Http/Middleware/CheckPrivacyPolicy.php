<?php

namespace Lara\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Lara\Http\Controllers\LoginController;

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
        if($request->isMethod('get') and !$request->is('privacy')) {
            if (Auth::check()) {
                if (!Auth::user()->privacy_accepted) {
                    return LoginController::redirectToPrivacyPage();
                }
            }
        }
        return $next($request);
    }
}
