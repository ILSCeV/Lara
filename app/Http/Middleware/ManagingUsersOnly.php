<?php

namespace Lara\Http\Middleware;

use Closure;
use Auth;
use Session;

class ManagingUsersOnly
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
        $user = Auth::user();

        if (!$user) {
            Session::put('message', trans("messages.notAllowed"));
            Session::put('msgType', 'danger');
            return Redirect('/');
        }

        if (!$user->is(['admin', 'marketing', 'clubleitung'])) {
            Session::put('message', trans("messages.notAllowed"));
            Session::put('msgType', 'danger');
            return Redirect('/');
        }

        return $next($request);
    }
}
