<?php

namespace Lara\Http\Middleware;

use Closure;

class Verify2FaToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($request->isMethod('get') and !($request->is('2fa') or $request->is('lang*'))) {
            if (\Auth::check()) {
                if (!empty(\Auth::user()->google2fa_secret ) and !\Session::get("2faVeryfied", false)) {
                    \Session::push('targeturl',$request->url());
                    return \Redirect::route('lara.2fa');
                }
            }
        }

        return $next($request);
    }
}
