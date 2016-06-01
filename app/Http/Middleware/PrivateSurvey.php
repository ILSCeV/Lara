<?php

namespace Lara\Http\Middleware;

use Closure;
use Lara\Survey;
use Redirect;

class PrivateSurvey
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
        $survey = Survey::findOrFail($request->route()->getParameter('survey'));
        if(!$survey->is_private
            OR $request->session()->get('userId')) {
            return $next($request);
        } else {
            $request->session()->put('message', 'Dir fehlt die nötige Berechtigung!');
            $request->session()->put('msgType', 'danger');
            return Redirect::action('MonthController@currentMonth');
        }
    }
}
