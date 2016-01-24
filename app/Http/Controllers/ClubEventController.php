<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;

use Lara\Http\Requests;
use Lara\Http\Controllers\Controller;

use Session;
use Cache;
use DateTime;
use DateInterval;
use View;

use Carbon\Carbon;

use Lara\ClubEvent;
use Lara\Schedule;
use Lara\ScheduleEntry;
use Lara\Jobtype;
use Lara\Person;
use Lara\Club;

class ClubEventController extends Controller
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
     * Generates the view for a specific event, including the schedule.
     *
     * @param  int $id
     * @return view ClubEventView
     * @return ClubEvent $clubEvent
     * @return ScheduleEntry[] $entries
     * @return RedirectResponse
     */
    public function show($id)
    {  
        $clubEvent = ClubEvent::with('getPlace')
                              ->findOrFail($id);
        
        if(!Session::has('userId') 
        AND $clubEvent->evnt_is_private==1)
            
        {
            Session::put('message', Config::get('messages_de.access-denied'));
            Session::put('msgType', 'danger');
            return Redirect::action('MonthController@showMonth', array('year' => date('Y'), 
                                                                   'month' => date('m')));
        }
    
        $schedule = Schedule::findOrFail($clubEvent->getSchedule->id);

        $entries = ScheduleEntry::where('schdl_id', '=', $schedule->id)
                                ->with('getJobType',
                                       'getPerson', 
                                       'getPerson.getClub')
                                ->get();

        $clubs = Club::orderBy('clb_title')->lists('clb_title', 'id');
        
        $persons = Cache::remember('personsForDropDown', 10 , function()
        {
            $timeSpan = new DateTime("now");
            $timeSpan = $timeSpan->sub(DateInterval::createFromDateString('3 months'));
            return Person::whereRaw("prsn_ldap_id IS NOT NULL AND (prsn_status IN ('aktiv', 'kandidat') OR updated_at>='".$timeSpan->format('Y-m-d H:i:s')."')")
                            ->orderBy('clb_id')
                            ->orderBy('prsn_name')
                            ->get();
        });

        $revisions = json_decode($clubEvent->getSchedule->entry_revisions, true);
        if (!is_null($revisions)) {
            // deleting ip adresses from output for privacy reasons
            foreach ($revisions as $entry) {
                unset($entry["from ip"]);
            }
        }
        
        return View::make('clubEventView', compact('clubEvent', 'entries', 'clubs', 'persons', 'revisions'));
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
}
