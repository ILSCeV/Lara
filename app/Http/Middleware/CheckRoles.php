<?php

namespace Lara\Http\Middleware;

use Closure;
use Auth;
use Redirect;

class CheckRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$params)
    {
        $user = Auth::user();

        if (!$user) {

            return Redirect('/');
        }

        $userGroup = $user->group;

        if (!in_array($userGroup, $params)) {
            return Redirect('/');
        }

        return $next($request);
    }
}
