<?php

namespace Lara\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Redirect;
use Lara\Survey;

class DeadlineSurvey
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
        $survey = Survey::findOrFail($request->route()->parameter('survey'));
        if(Carbon::now() < Carbon::createFromTimestamp(strtotime($survey->deadline))
            OR $request->session()->get('userGroup') == "clubleitung"
            OR $request->session()->get('userGroup') == "marketing"
            OR $request->session()->get('userGroup') == "admin") {
            return $next($request);
        } else {
            $request->session()->put('message', 'Die Deadline ist überschritten, jetzt können nurnoch Clubleitung/Marketing/Admin die Umfrage ausfüllen');
            $request->session()->put('msgType', 'danger');
            return Redirect::action('MonthController@currentMonth');
        }
    }
}
