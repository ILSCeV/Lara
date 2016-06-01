<?php

namespace Lara\Http\Middleware;

use Closure;
use Lara\Survey;
use Redirect;

class CreatorSurvey
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
        if($survey->prsn_id == $request->session()->get('userId')
            OR $request->session()->get('userGroup') == "clubleitung"
            OR $request->session()->get('userGroup') == "marketing"
            OR $request->session()->get('userGroup') == "admin") {
            return $next($request);
        } else {
            $request->session()->put('message', 'Dir fehlt die nötige Berechtigung!');
            $request->session()->put('msgType', 'danger');
            return Redirect::action('MonthController@currentMonth');
        }
    }
}
