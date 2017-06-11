<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabian
 * Date: 11.06.17
 * Time: 18:57
 */

namespace Lara\Http\Controllers;


use Lara\Utilities;

class LaraUpgradeController extends Controller
{
    function startUpgradeProcess(){
        if(!Utilities::requirePermission('admin')){
            return \Redirect::back($status = 403);
        }
        return \View::make("upgrade");
    }
}