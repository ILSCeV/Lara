<?php

namespace Lara\Http\Middleware;

use Closure;
use Lara\Survey;
use PhpParser\Node\Scalar\String_;
use Redirect;

class Creator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $classname
     * @return mixed
     */
    public function handle($request, Closure $next, $classpath, $routeParameterName)
    {
        $newObject = new $classpath();
        $object = $newObject->findOrFail($request->route()->parameter($routeParameterName));
        if($object->creator_id == $request->session()->get('userId')
            || $request->session()->get('userGroup') == "clubleitung"
            || $request->session()->get('userGroup') == "marketing"
            || $request->session()->get('userGroup') == "admin") {
            return $next($request);
        } else {
            $request->session()->put('message', 'Dir fehlt die nötige Berechtigung!');
            $request->session()->put('msgType', 'danger');
            return Redirect::action('MonthController@currentMonth');
        }
    }
}
