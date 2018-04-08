<?php

namespace Lara\Http\Middleware;

use Closure;
use Lara\Survey;
use Lara\utilities\RoleUtility;
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
        if($object->creator_id == \Auth::user()->person->prsn_ldap_id
            || \Auth::user()->is(RoleUtility::PRIVILEGE_ADMINISTRATOR)
            || \Auth::user()->hasPermissionsInSection($object->section()
                , [RoleUtility::PRIVILEGE_CL, RoleUtility::PRIVILEGE_MARKETING])
            ) {
            return $next($request);
        } else {
            $request->session()->put('message', 'Dir fehlt die nötige Berechtigung!');
            $request->session()->put('msgType', 'danger');
            return Redirect::action('MonthController@currentMonth');
        }
    }
}
