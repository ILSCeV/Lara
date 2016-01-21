<?php

namespace Lara\Http\Controllers;

use Request;
use Session;
use Input;

use Carbon\Carbon;
use \Datetime;

use Lara\Http\Requests;
use Lara\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Updates entry revision
     *
     * @param Schedule $schedule     
     * @param ScheduleEntry $entry
     * @param string $action
     * @param string $old
     * @param string $new
     * @return void
     */
    public static function logRevision($schedule, $entry, $action, $old, $new)
    {
        // workaround for older events where revision history is not present
        if($schedule->entry_revisions == "")
        {
            $schedule->entry_revisions = json_encode(["0" => ["entry id" => "",
                                                      "job type" => "",
                                                      "action" => "Keine frühere Änderungen vorhanden.",
                                                      "old id" => "",
                                                      "old value" => "",
                                                      "new id" => "",
                                                      "new value" => "",
                                                      "user id" => "",
                                                      "user name" => "",
                                                      "from ip" => "",
                                                      "timestamp" => (new DateTime)->format('d.m.Y H:i:s')]]);
        }
    
        // decode revision history
        $revisions = json_decode($schedule->entry_revisions, true);

        // decode old values
        if(!is_null($old)){
            $oldId = $old->id;

            switch ($old->prsn_status) {
                case "candidate":
                    $oldStatus = "(K)";
                    break;
                case "member":
                    $oldStatus = "(A)";
                    break;
                case "veteran":
                    $oldStatus = "(V)";
                    break;
                default: 
                    $oldStatus = "";
            }

            $oldName = $old->prsn_name
                     . $oldStatus 
                     . "(" . $old->getClub->clb_title . ")";
        }
        else
        {
            $oldId = "";
            $oldName = "";
        }

        // decode new values
        if(!is_null($new)){
            $newId = $new->id;
            
            switch ($new->prsn_status) {
                case "candidate":
                    $newStatus = "(K)";
                    break;
                case "member":
                    $newStatus = "(A)";
                    break;
                case "veteran":
                    $newStatus = "(V)";
                    break;
                default: 
                    $newStatus = "";
            }

            $newName = $new->prsn_name 
                     . $newStatus
                     . "(" . $new->getClub->clb_title . ")";
        }
        else
        {
            $newId = "";
            $newName = "";
        }
        
        // append current change
        array_push($revisions, ["entry id" => $entry->id,
                                "job type" => $entry->getJobType->jbtyp_title,
                                "action" => $action,
                                "old id" => $oldId,
                                "old value" => $oldName,
                                "new id" => $newId,
                                "new value" => $newName,
                                "user id" => Session::get('userId') != NULL ? Session::get('userId') : "",
                                "user name" => Session::get('userId') != NULL ? Session::get('userName') . '(' . Session::get('userClub') . ')' : "Gast",
                                "from ip" => Request::getClientIp(),
                                "timestamp" => (new DateTime)->format('d.m.Y H:i:s')]
                    );      

        // encode and save
        $schedule->entry_revisions = json_encode($revisions);
                        
        $schedule->save();
    }
}
