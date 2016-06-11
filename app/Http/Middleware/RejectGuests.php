<?php

namespace Lara\Http\Middleware;

use Closure;
use Redirect;

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
        if($request->session()->get('userId')) {
            return $next($request);
        } else {
            $request->session()->put('message', 'Bitte einloggen!');
            $request->session()->put('msgType', 'danger');
            return Redirect::action('MonthController@currentMonth');
        }
    }
}
