<?php

namespace Lara\Http\Middleware;

use Closure;
use Redirect;
use Auth;
use Session;

class RejectGuests
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
        if (Auth::check()) {
            return $next($request);
        } else {
            Session::put('message', 'Bitte einloggen');
            Session::put('msgType', 'danger');
            return Redirect('/');
        }
    }
}
