<?php

namespace Lara\Http\Middleware;

use Closure;
use Redirect;
use Auth;


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
            session()->put('message', 'Bitte einloggen');
            session()->put('msgType', 'danger');
            return Redirect('/');
        }
    }
}
