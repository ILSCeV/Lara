<?php

namespace Lara\Http\Controllers;

use Cache;
use Config;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Input;
use Lara\Club;
use Lara\ClubEvent;
use Lara\Logging;
use Lara\ShiftType;
use Lara\Shift;
use Lara\Person;
use Lara\Place;
use Lara\Schedule;
use Lara\Utilities;
use Log;
use Redirect;
use Session;
use View;

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
     * @param  int $year
     * @param  int $month
     * @param  int $day
     *
     * @return view createClubEventView
     * @return Place[] places
     * @return Schedule[] templates
     * @return Jobtype[] jobtypes
     * @return string $date
     * @return \Illuminate\Http\Response
     */
    public function create($year = null, $month = null, $day = null, $templateId = null)
    {
        // Filling missing date and template number in case none are provided
        if ( is_null($year) ) {
            $year = date("Y");
        }

        if ( is_null($month) ) {
            $month = date("m");
        }

        if ( is_null($day) ) {
            $day = date("d");
        }

        if ( is_null($templateId) ) {
            $templateId = 0;    // 0 = no template
        }

        // prepare correct date format to be used in the forms
        $date = strftime("%d-%m-%Y", strtotime($year.$month.$day));

        // get a list of possible clubs to create an event at, but without the id=0 (default value)
        $places = Place::where("id", '>', 0)
                       ->orderBy('plc_title', 'ASC')
                       ->pluck('plc_title', 'id');

        // get a list of available templates to choose from
        $templates = Schedule::where('schdl_is_template', '=', '1')
                             ->orderBy('schdl_title', 'ASC')
                             ->get();

        // get a list of available job types
        $jobtypes = ShiftType::where('jbtyp_is_archived', '=', '0')
                           ->orderBy('jbtyp_title', 'ASC')
                           ->get();

        // if a template id was provided, load the schedule needed and extract job types
        if ( $templateId != 0 ) {
            $template = Schedule::where('id', '=', $templateId)
                                ->first();

            // put name of the active template for further use
            $activeTemplate = $template->schdl_title;

            // get template data
            $entries    = $template->shifts()->with('type')->orderByRaw('position IS NULL, position ASC, id ASC')->get();
            $title      = $template->getClubEvent->evnt_title;
            $subtitle   = $template->getClubEvent->evnt_subtitle;
            $type       = $template->getClubEvent->evnt_type;
            $place      = $template->getClubEvent->plc_id;
            $filter     = $template->getClubEvent->evnt_show_to_club;
            $dv         = $template->schdl_time_preparation_start;
            $timeStart  = $template->getClubEvent->evnt_time_start;
            $timeEnd    = $template->getClubEvent->evnt_time_end;
            $info       = $template->getClubEvent->evnt_public_info;
            $details    = $template->getClubEvent->evnt_private_details;
            $private    = $template->getClubEvent->evnt_is_private;
        } else {
            // fill variables with no data if no template was chosen
            $activeTemplate = "";
            $entries    = collect([]);
            $title      = null;
            $type       = null;
            $subtitle   = null;
            $place      = null;
            $filter     = null;
            $dv         = null;
            $timeStart  = null;
            $timeEnd    = null;
            $info       = null;
            $details    = null;
            $private    = null;
        }

        return View::make('createClubEventView', compact('places', 'jobtypes', 'templates',
                                                         'entries', 'title', 'subtitle', 'type',
                                                         'place', 'filter', 'timeStart', 'timeEnd',
                                                         'info', 'details', 'private', 'dv',
                                                         'activeTemplate',
                                                         'date'));
    }


    /**
     * Store a newly created resource in storage.
     * Create a new event with schedule and write it to the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate passwords
        if (Input::get('password') != Input::get('passwordDouble')) {
            Session::put('message', Config::get('messages_de.password-mismatch') );
            Session::put('msgType', 'danger');
            return Redirect::back()->withInput();
            }

        $newEvent = $this->editClubEvent(null);
        $newEvent->save();

        $newSchedule = (new ScheduleController)->update(null);
        $newSchedule->evnt_id = $newEvent->id;

        // log revision
        Logging::scheduleCreated($newSchedule);

        $newSchedule->save();

        ScheduleController::createShifts($newSchedule->id);

        // log the action
        Log::info('Event created: ' . Session::get('userName') . ' (' . Session::get('userId') . ', '
                 . Session::get('userGroup') . ') created event "' . $newEvent->evnt_title . '" (eventID: ' . $newEvent->id . ') on ' . $newEvent->evnt_date_start . '.');
        Utilities::clearIcalCache();
        // show new event
        return Redirect::action('ClubEventController@show', array('id' => $newEvent->id));
    }

    /**
     * Generates the view for a specific event, including the schedule.
     *
     * @param  int $id
     * @return view ClubEventView
     * @return ClubEvent $clubEvent
     * @return Shift[] $entries
     * @return RedirectResponse
     */
    public function show($id)
    {
        $clubEvent = ClubEvent::with('getPlace')
                              ->findOrFail($id);

        if(!Session::has('userId') && $clubEvent->evnt_is_private==1)
        {
            Session::put('message', Config::get('messages_de.access-denied'));
            Session::put('msgType', 'danger');
            return Redirect::action('MonthController@showMonth', array('year' => date('Y'),
                                                                   'month' => date('m')));
        }

        //ignore html tags in the info boxes
        $clubEvent->evnt_public_info = htmlspecialchars($clubEvent->evnt_public_info, ENT_NOQUOTES);
        $clubEvent->evnt_private_details = htmlspecialchars($clubEvent->evnt_private_details, ENT_NOQUOTES);

        //if URL is in one of the info boxes, convert it to clickable hyperlink (<a> tag)
        $clubEvent->evnt_public_info = Utilities::surroundLinksWithTags($clubEvent->evnt_public_info);
        $clubEvent->evnt_private_details = Utilities::surroundLinksWithTags($clubEvent->evnt_private_details);

        $schedule = Schedule::findOrFail($clubEvent->getSchedule->id);

        $entries = Shift::where('schedule_id', '=', $schedule->id)
                                ->with('type',
                                       'getPerson',
                                       'getPerson.getClub')
                                ->orderByRaw('position IS NULL, position ASC, id ASC')
                                ->get();

        $clubs = Club::orderBy('clb_title')->pluck('clb_title', 'id');

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
            // reverse order to show latest revision first
            $revisions = array_reverse($revisions);

            // deleting ip adresses from output for privacy reasons
            foreach ($revisions as $entry) {
                unset($entry["from ip"]);
            }
            // add LDAP-ID of the event creator - only this user + Marketing + CL will be able to edit
            $created_by = $revisions[0]["user id"];
            $creator_name = $revisions[0]["user name"];
        }
        else {
            // workaround for empty revision in development
            $revisions = [];
            $created_by = "";
            $creator_name = "";
        }

        // Filter - Workaround for older events: populate filter with event club
        if (empty($clubEvent->evnt_show_to_club) ) {
            $clubEvent->evnt_show_to_club = json_encode([$clubEvent->getPlace->plc_title], true);
            $clubEvent->save();
        }

        return View::make('clubEventView', compact('clubEvent', 'entries', 'clubs', 'persons', 'revisions', 'created_by', 'creator_name'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // find event
        $event = ClubEvent::findOrFail($id);

        // find schedule
        $schedule = $event->getSchedule;

        // get a list of possible clubs to create an event at
        $places = Place::orderBy('plc_title', 'ASC')
                       ->pluck('plc_title', 'id');


        // get a list of available job types
        $jobtypes = ShiftType::where('jbtyp_is_archived', '=', '0')
                           ->orderBy('jbtyp_title', 'ASC')
                           ->get();

        // put template data into entries
        $entries = $schedule->shifts()
                            ->with('type')
                            ->orderByRaw('position IS NULL, position ASC, id ASC')
                            ->get();

        // Filter - Workaround for older events: populate filter with event club
        if (empty($event->evnt_show_to_club) ) {
            $event->evnt_show_to_club = json_encode([$event->getPlace->plc_title], true);
            $event->save();
        }

        // add LDAP-ID of the event creator - only this user + Marketing + CL will be able to edit
        $revisions = json_decode($event->getSchedule->entry_revisions, true);
        if (is_null($revisions)) {
            $created_by = "";
            $creator_name = "";
        }
        else {
            $created_by = $revisions[0]["user id"];
            $creator_name = $revisions[0]["user name"];
        }


        return View::make('editClubEventView', compact('event',
                                                       'schedule',
                                                       'places',
                                                       'jobtypes',
                                                       'entries',
                                                       'created_by',
                                                       'creator_name'));
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
        //validate passwords
        if (Input::get('password') != Input::get('passwordDouble')) {
            Session::put('message', Config::get('messages_de.password-mismatch') );
            Session::put('msgType', 'danger');
            return Redirect::back()->withInput();
        }

        // first we fill objects with data
        // if there is an error, we have not saved yet, so we need no rollback
        $event = $this->editClubEvent($id);

        $schedule = (new ScheduleController)->update($event->getSchedule->id);

        ScheduleController::editShifts($schedule->id);

        // log the action
        Log::info('Event edited: ' . Session::get('userName') . ' (' . Session::get('userId') . ', '
                 . Session::get('userGroup') . ') edited event "' . $event->evnt_title . '" (eventID: ' . $event->id . ') on ' . $event->evnt_date_start . '.');


        // save all data in the database
        $event->save();
        $schedule->save();

        Utilities::clearIcalCache();

        // show event
        return Redirect::action('ClubEventController@show', array('id' => $id));
    }

    /**
     * Delete an event specified by parameter $id with schedule and schedule entries.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        // Get all the data
        $event = ClubEvent::find($id);


        // Check if event exists
        if ( is_null($event) ) {
            Session::put('message', Config::get('messages_de.event-doesnt-exist') );
            Session::put('msgType', 'danger');
            return Redirect::back();
        }
        
        // Check credentials: you can only delete, if you have rights for marketing or management. 
        $revisions = json_decode($event->getSchedule->entry_revisions, true);
        $created_by = $revisions[0]["user id"];
        if(!Session::has('userId')
            OR (Session::get('userGroup') != 'marketing'
                AND Session::get('userGroup') != 'clubleitung'
                AND Session::get('userGroup') != 'admin'
                AND Session::get('userId') != $created_by))
        {
            Session::put('message', 'Du darfst diese Veranstaltung/Aufgabe nicht einfach löschen! Frage die Clubleitung oder Markleting ;)');
            Session::put('msgType', 'danger');
            return Redirect::action('MonthController@showMonth', array('year' => date('Y'),
                                                                   'month' => date('m')));
        }

        // Log the action while we still have the data
        Log::info('Event deleted: ' . Session::get('userName') . ' (' . Session::get('userId') . ', '
                 . Session::get('userGroup') . ') deleted event "' . $event->evnt_title . '" (eventID: ' . $event->id . ') on ' . $event->evnt_date_start . '.');
        Utilities::clearIcalCache();

        // Delete schedule with entries
        $result = (new ScheduleController)->destroy($event->getSchedule()->first()->id);

        // Now delete the event itself
        ClubEvent::destroy($id);

        // show current month afterwards
        Session::put('message', Config::get('messages_de.event-delete-ok'));
        Session::put('msgType', 'success');
        return Redirect::action( 'MonthController@showMonth', ['year' => date("Y"),
                                                               'month' => date('m')] );
    }





//--------- PRIVATE FUNCTIONS ------------


    /**
    * Edit or create a clubevent with its entered information.
    * If $id is null create a new clubEvent, otherwise the clubEvent specified by $id will be edit.
    *
    * @param int $id
    * @return ClubEvent clubEvent
    */
    private function editClubEvent($id)
    {
        $event = new ClubEvent;
        if(!is_null($id)) {
            $event = ClubEvent::findOrFail($id);
        }

        // format: strings; no validation needed
        $event->evnt_title           = Input::get('title');
        $event->evnt_subtitle        = Input::get('subtitle');
        $event->evnt_public_info     = Input::get('publicInfo');
        $event->evnt_private_details = Input::get('privateDetails');
        $event->evnt_type            = Input::get('evnt_type');

        // create new place
        if (!Place::where('plc_title', '=', Input::get('place'))->first())
        {
            $place = new Place;
            $place->plc_title = Input::get('place');
            $place->save();

            $event->plc_id = $place->id;
        }
        // use existing place
        else
        {
            $event->plc_id = Place::where('plc_title', '=', Input::get('place'))->first()->id;
        }

        // format: date; validate on filled value  
        if(!empty(Input::get('beginDate')))
        {
            $newBeginDate = new DateTime(Input::get('beginDate'), new DateTimeZone(Config::get('app.timezone')));
            $event->evnt_date_start = $newBeginDate->format('Y-m-d');
        }
        else
        {
            $event->evnt_date_start = date('Y-m-d', mktime(0, 0, 0, 0, 0, 0));;
        }

        if(!empty(Input::get('endDate')))
        {
            $newEndDate = new DateTime(Input::get('endDate'), new DateTimeZone(Config::get('app.timezone')));
            $event->evnt_date_end = $newEndDate->format('Y-m-d');
        }
        else
        {
            $event->evnt_date_end = date('Y-m-d', mktime(0, 0, 0, 0, 0, 0));;
        }

        // format: time; validate on filled value
        $event->evnt_time_start = !empty(Input::get('beginTime')) ? Input::get('beginTime') : mktime(0, 0, 0);
        $event->evnt_time_end = !empty(Input::get('endTime')) ? Input::get('endTime') : mktime(0, 0, 0);

        // format: tinyInt; validate on filled value
        // reversed this: input=1 means "event is public", input=0 means "event is private"
        $event->evnt_is_private = (Input::get('isPrivate') == '1') ? 0 : 1;
        $eventIsPublished = Input::get('evntIsPublished');
        
        if (!is_null($eventIsPublished)) {
            //event is pubished
            $event->evnt_is_published = (int)$eventIsPublished;
        } elseif (Session::get('userGroup') == 'marketing' OR Session::get('userGroup') == 'clubleitung'  OR Session::get('userGroup') == 'admin'){
            // event was unpublished
            $event->evnt_is_published = 0;
        }
        // Filter
        $filter = [];
        if (Input::get('filterShowToClub2') == '1') { array_push($filter, 'bc-Club'); }
        if (Input::get('filterShowToClub3') == '1') { array_push($filter, 'bc-Café'); }
        $event->evnt_show_to_club = json_encode($filter, true);

        // Logging

        if ($event->exists) {
            //only log changes if the event already exists in the DB
            if ($event->isDirty('evnt_time_start')) {
                Logging::eventStartChanged($event);
            }

            if ($event->isDirty('evnt_title')) {
                Logging::eventTitleChanged($event);
            }

            if ($event->isDirty('evnt_subtitle')) {
                Logging::eventSubtitleChanged($event);
            }

            if ($event->isDirty('evnt_time_end')) {
                Logging::eventEndChanged($event);
            }

            if ($event->isDirty('evnt_public_info')) {
                Logging::logEventRevision($event, "revisions.eventPublicInfoChanged");
            }

            if ($event->isDirty('evnt_private_details')) {
                Logging::logEventRevision($event, "revisions.eventPrivateDetailsChanged");
            }

        }
        return $event;
    }
}



