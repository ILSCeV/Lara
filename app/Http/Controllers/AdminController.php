<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabian
 * Date: 11.06.17
 * Time: 18:57
 */

namespace Lara\Http\Controllers;



use Lara\Utilities;

/** controller to handle lara upgrade directly */
class AdminController extends Controller
{
    /** handles the upgrade request */
    function startUpdateProcess(){
        /** if you are no admin, you will be redirected back */
        if(!Utilities::requirePermission('admin')){
            return \Redirect::back($status = 403);
        }
    
        $cmd = "php artisan lara:update &";
        $descriptorspec = array(
            0 => array("pipe", "r"),   // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),   // stdout is a pipe that the child will write to
            2 => array("pipe", "w")    // stderr is a pipe that the child will write to
        );
        
        proc_open($cmd, $descriptorspec, $pipes, realpath('../'), array());
        
        return \Redirect::back();
    }
}