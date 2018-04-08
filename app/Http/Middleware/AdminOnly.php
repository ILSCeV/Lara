<?php

namespace Lara\Http\Middleware;

use Closure;
use Lara\Utilities;
use Lara\utilities\RoleUtility;
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
            Utilities::error(trans('auth.notAuthenticated'));
            return Redirect('/');
        }

        if (!Auth::user()->isAn(RoleUtility::PRIVILEGE_ADMINISTRATOR)) {
            Utilities::error(trans('auth.missingPermissions'));
            return Redirect('/');
        }

        return $next($request);
    }
}
