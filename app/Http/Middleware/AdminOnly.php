<?php

namespace Lara\Http\Middleware;

use Closure;
use Redirect;
use Auth;

class AdminOnly
{
    /**
     * Deny access for guest and users without admin privileges
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return Redirect('/');
        }

        if (Auth::user()->group !== 'admin') {
            return Redirect('/');
        }

        return $next($request);
    }
}
