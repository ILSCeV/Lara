<?php

namespace Lara\Http\Controllers;

use DateTime;
use Request;
use Session;
use Input;
use Hash;
use Illuminate\Database\Eloquent\Collection;

use Lara\Schedule;
use Lara\Shift;
use Lara\ShiftType;

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
    * Edit or create a schedule with its entered information.
    * If $scheduleId is null create a new schedule, otherwise the schedule specified by $scheduleId will be edited.
    *
    * Should be static to be accessed from ClubEventController
    *
    * @param int $scheduleId
    * @return Schedule newSchedule
     */
    public function update($scheduleId)
    {
        $schedule = new Schedule;

        if (!is_null($scheduleId))
        {
            $schedule = Schedule::findOrFail($scheduleId);
        }

        // format: time; validate on filled value
        if(!empty(Input::get('preparationTime'))) 
        {
            $schedule->schdl_time_preparation_start = Input::get('preparationTime');
        }
        else
        { 
            $schedule->schdl_time_preparation_start = mktime(0, 0, 0);
        }

        // format: password; validate on filled value
        if (Input::get('password') == "delete" 
        AND Input::get('passwordDouble') == "delete") 
        {
            $schedule->schdl_password = '';
        } 
        elseif (!empty(Input::get('password'))
            AND !empty(Input::get('passwordDouble'))
            AND Input::get('password') == Input::get('passwordDouble')) 
        {
            $schedule->schdl_password = Hash::make(Input::get('password'));
        }

        // format: tinyInt; validate on filled value
        if (Input::get('saveAsTemplate') == true)
        {
            $schedule->schdl_is_template = true;
            $schedule->schdl_title = Input::get('templateName');
        }
        else 
        {
            $schedule->schdl_is_template = false;
        }

        return $schedule;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy($id)
    {
        // Get all the data
        $schedule = Schedule::find($id);

        // Check if the schedule exists
        if ( is_null($schedule) ) {
            Session::put('message', 'Fehler: Löschvorgang abgebrochen - der Dienstplaneintrag existiert nicht.');
            Session::put('msgType', 'danger');
            return Redirect::back();
        }
        // Delete all corresponding entries first because of dependencies in database
        foreach ( $schedule->shifts->get() as $shift ) {
            $result = (new ShiftController)->destroy($shift->id);
        }

        // Delete the schedule
        Schedule::destroy($id);
    }


    /**
     * Updates entry revision
     *
     * @param Schedule $schedule     
     * @param Shift $shift
     * @param string $action
     * @param string $old
     * @param string $new
     * @param string $oldComment
     * @param string $newComment
     * @return void
     */
    public static function logRevision($schedule, Shift $shift, $action, $old, $new, $oldComment, $newComment)
    {
        // workaround for older events where revision history is not present
        if($schedule->entry_revisions == "")
        {
            $schedule->entry_revisions = json_encode(["0" => ["entry id"    => "",
                                                              "job type"    => "",
                                                              "action"      => "Keine frühere Änderungen vorhanden.",
                                                              "old id"      => "",
                                                              "old value"   => "",
                                                              "old comment" => "",
                                                              "new id"      => "",
                                                              "new value"   => "",
                                                              "user id"     => "",
                                                              "user name"   => "",
                                                              "new comment" => "",
                                                              "from ip"     => "",
                                                              "timestamp"   => (new DateTime)->format('d.m.Y H:i:s') ]
                                                     ]);
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
        array_push($revisions, ["entry id"    => $shift->id,
                                "job type"    => $shift->type->title(),
                                "action"      => $action,
                                "old id"      => $oldId,
                                "old value"   => $oldName,
                                "old comment" => $oldComment,
                                "new id"      => $newId,
                                "new value"   => $newName,
                                "new comment" => $newComment,
                                "user id"     => Session::get('userId') != NULL ? Session::get('userId') : "",
                                "user name"   => Session::get('userId') != NULL ? Session::get('userName') . ' (' . Session::get('userClub') . ')' : "Gast",
                                "from ip"     => Request::getClientIp(),
                                "timestamp"   => (new DateTime)->format('d.m.Y H:i:s') ]
                    );      

        // encode and save
        $schedule->entry_revisions = json_encode($revisions);
                        
        $schedule->save();
    }


    /**
    * Create all new scheduleEntries with entered information.
    *
    * @return Collection scheduleEntries
    */
    public static function createShifts()
    {
        $scheduleEntries = new Collection;

        // parsing shiftType entries
        for ($i=1; $i <= Input::get("counter"); $i++) {

            // skip empty fields
            if (!empty(Input::get("jbtyp_title" . $i))) 
            {       

                // check if job type exists
                $shiftType = ShiftType::where('jbtyp_title', '=', Input::get("jobType" . $i))
                                  ->where('jbtyp_time_start', '=', Input::get("timeStart" . $i))
                                  ->where('jbtyp_time_end', '=', Input::get("timeEnd" . $i))
                                  ->first();
                
                // If not found - create new job type with data provided
                if (is_null($shiftType))
                {
                    $shiftType = ShiftType::create([
                        'jbtyp_title' => Input::get("jbtyp_title" . $i),
                        'jbtyp_time_start' => Input::get('jbtyp_time_start' . $i),
                        'jbtyp_time_end' => Input::get('jbtyp_time_end' . $i),
                        'jbtyp_statistical_weight' => Input::get('jbtyp_statistical_weight' . $i),
                        'jbtyp_needs_preparation' => 'true',
                        'jbtyp_is_archived' => 'false'
                    ]);
                }

                $shift = new Shift;
                $shift->jbtyp_id = $shiftType->id;

                // save changes
                $scheduleEntries->add(ScheduleController::updateShift($shift, $shiftType->id, $i));
            }
        }

        return $scheduleEntries;
    }


    /**
    * Edit and/or delete scheduleEntries refered to $scheduleId.
    *
    * @param Schedule $schedule
    * @return Collection scheduleEntries
    */
    public static function editShifts($scheduleId)
    {
        // get number of submitted entries
        $numberOfSubmittedEntries = Input::get('counter');

        // get old entries for this schedule
        $shifts = Shift::where('schdl_id', '=', $scheduleId)->get();

        // prepare a collection for updated entries
        $newEntries = new Collection;

        // Counter to traverse all inputs from 1 to N
        $counterHelper = '1';

        // check for changes in each shift
        foreach ( $shifts as $shift )
        {

            // same job type as before - do nothing
            if ( $shift->type == Input::get('jbtyp_title' . $counterHelper) )
            {
                // add to new collection
                $newEntries->add(ScheduleController::updateShift($shift, $shift->type->id, $counterHelper));

            } 
            // job type empty - delete shift
            elseif ( Input::get("jbtyp_title" . $counterHelper) == '' ) 
            {
                // log revision
                ScheduleController::logRevision($shift->getSchedule,    // schedule object
                                                $shift,                 // shift object
                                                "Dienst gelöscht",      // action description
                                                $shift->getPerson,      // old value
                                                null,                   // new value
                                                null,                   // old comment
                                                null);                  // new comment

                // proceed with deletion
                $shift->delete();

            } 
            // some new job type added - change shift
            else 
            {       
                $shiftType = ShiftType::firstOrCreate(array('jbtyp_title'=>Input::get("jbtyp_title" . $counterHelper)));
                $shift->jbtyp_id = $shiftType->id;

                // log revision
                /*
                ScheduleController::logRevision($shift->getSchedule,    // schedule object
                                                $shift,                 // shift object
                                                "Dienst aktualisiert",      // action description
                                                $shift->getPerson,      // old value
                                                $shift->getPerson);     // new value
                */
                // add to new collection
                $newEntries->add(ScheduleController::updateShift($shift, $shiftType->id, $counterHelper));
            }

            // move to next input
            $counterHelper++;
        }

        // At this point we changed all existing entries - have any new ones been added?

        if ($numberOfSubmittedEntries > $counterHelper - 1) {
            
            // create some new fields
            for ($i= $counterHelper; $i <= $numberOfSubmittedEntries; $i++) 
            {
                // skip empty fields, create new fields only if input not empty
                if (!empty(Input::get("jbtyp_title" . $i))) 
                {
                    $shiftType = ShiftType::firstOrCreate(array('jbtyp_title'=>Input::get("jbtyp_title" . $i)));

                    $newShift = new Shift;
                    $newShift->jbtyp_id = $shiftType->id;
                    $newShift->schdl_id = $scheduleId;

                    // log revision
                    ScheduleController::logRevision($newShift->getSchedule, // schedule object
                                                    $newShift,              // shift object
                                                    "Dienst erstellt",      // action description
                                                    null,                   // old value
                                                    null,                   // new value
                                                    null,                   // old comment
                                                    null);                  // new comment                   

                    // add to new collection
                    $newEntries->add(ScheduleController::updateShift($newShift, $shiftType->id, $i));
                }
            }
        }

        return $newEntries;
    }

    /**
     * Receives a timestamp, compares it to last update time of the schedule 
     * and returns either a false boolean for "no updates since timestamp provided"
     * or a JSON array of updated schedule entries
     *
     * @param int $scheduleId
     * @param String $timestamp
     *
     * @return \Illuminate\Http\Response 
     */
    public static function getUpdates($scheduleId, $timestamp) 
    {
        $updated = Schedule::where("id", "=", $id)->first()->updated_at;
        return response()->json($updated, 200);
    }


    /**
    * Update start and end time of $shift with input of gui elements
    *
    * @param Shift $shift
    * @param int $jobtypeId
    * @param int $counterValue
    * @return Shift updates shift
    */
    private static function updateShift($shift, $jobtypeId, $counterValue)
    {
        $shift->entry_time_start = Input::get('jbtyp_time_start' . $counterValue);

        $shift->entry_time_end = Input::get('jbtyp_time_end' . $counterValue);

        $shift->entry_statistical_weight = Input::get('jbtyp_statistical_weight' . $counterValue);

        return $shift;
    }
    
}
