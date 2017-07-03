<?php

namespace Lara\Http\Controllers;

use DateTime;
use Request;
use Session;
use Input;
use Hash;
use Lara\Logging;
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

        if ($schedule->exists && $schedule->isDirty('schdl_time_preparation_start')) {
            Logging::preparationTimeChanged($schedule);
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
    * Create all new scheduleEntries with entered information.
    *
    * @return Collection scheduleEntries
    */
    public static function createShifts($scheduleId, $isNewEvent = true)
    {
        $inputShifts = Input::get("shifts");
        $amount = count($inputShifts["title"]);

        Shift::where('schedule_id', $scheduleId)
            ->whereNotIn('id', $inputShifts["id"])
            ->get()
            ->each(function(Shift $shift) {
                Logging::shiftDeleted($shift);
                $shift->delete();
            });

        for ($i = 0; $i < $amount; ++$i) {
            if ($inputShifts["title"][$i] !== "") {
                // Cloned shifts have the "id" field set to "", so we will create a new model in this case
                $shift = Shift::firstOrNew(["id" => $inputShifts["id"][$i]]);

                // If there was a shifttype passed and one with the correct title exists, use this one
                // Otherwise create a new model
                $oldShiftType = $shift->type;
                
                // we need a raw statement for case sensitivity
                $shiftType = ShiftType::whereRaw("BINARY `jbtyp_title`= ?", $inputShifts["title"][$i])->where('id', $inputShifts["type"][$i])
                    ->first();
                if (is_null($shiftType)) {
                    $shiftType = new ShiftType([
                        "id" => $inputShifts["type"][$i],
                        "jbtyp_title" => $inputShifts["title"][$i]
                    ]);
                }

                // if the model was newly created, save the new shiftType
                if (! $shiftType->exists) {
                    $shiftType->fill([
                        'jbtyp_time_start' => $inputShifts["start"][$i],
                        'jbtyp_time_end' => $inputShifts["end"][$i],
                        'jbtyp_statistical_weight' => $inputShifts["weight"][$i],
                    ]);
                    $shiftType->save();
                }
                $shift->fill([
                    "schedule_id" => $scheduleId,
                    "start" => $inputShifts["start"][$i],
                    "end" => $inputShifts["end"][$i],
                    "statistical_weight" => $inputShifts["weight"][$i],
                    "shifttype_id" => $shiftType->id,
                    "position" => $i
                ]);

                if ($shift->exists) {
                    if ($shift->isDirty('shifttype_id')) {
                        Logging::shiftTypeChanged($shift, $oldShiftType, $shiftType);
                    }

                    if ($shift->isDirty('statistical_weight')) {
                        Logging::shiftStatisticalWeightChanged($shift);
                    }

                    if ($shift->isDirty('start')) {
                        Logging::shiftStartChanged($shift);
                    }

                    if ($shift->isDirty('end')) {
                        Logging::shiftEndChanged($shift);
                    }
                }
                else if (!$isNewEvent){
                    $shift->save();
                    Logging::shiftCreated($shift);
                }

                $shift->save();
            }
        }
    }


    /**
    * Edit and/or delete scheduleEntries refered to $scheduleId.
    *
    * @param Schedule $schedule
    * @return Collection scheduleEntries
    */
    public static function editShifts($scheduleId)
    {
        self::createShifts($scheduleId, false);
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
        $updated = Schedule::where("id", "=",  $id)->first()->updated_at;
        return response()->json($updated, 200);
    }

}
