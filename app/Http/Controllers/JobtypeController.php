<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;

use Lara\Http\Requests;
use Lara\Http\Controllers\Controller;
use Lara\Jobtype;
use Lara\ScheduleEntry;
use Lara\Schedule;
use Session;
use Log;
use Redirect;
use View;

class JobtypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobtypes = Jobtype::orderBy('jbtyp_title', 'ASC')->paginate(25);

        return view('manageJobtypesView', ['jobtypes' => $jobtypes]);
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
        // get selected jobtype
        $current_jobtype = Jobtype::findOrFail($id);

        // get a list of all available job types
        $jobtypes = Jobtype::orderBy('jbtyp_title', 'ASC')->get();

        $entries = ScheduleEntry::where('jbtyp_id', '=', $id)->with('schedule.event.getPlace')->paginate(25);

        return View::make('jobTypeView', compact('current_jobtype', 'jobtypes', 'entries'));
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
        // Check credentials: you can only delete, if you have rights for marketing, section management or admin
        if(!Session::has('userId') 
            OR (Session::get('userGroup') != 'marketing'
                AND Session::get('userGroup') != 'clubleitung'
                AND Session::get('userGroup') != 'admin'))
        {
            Session::put('message', 'Du darfst das nicht einfach löschen! Frage die Clubleitung oder Markleting ;)');
            Session::put('msgType', 'danger');
            return Redirect::back();
        }

        // Get all the data 
        // (throws a 404 error if jobtype doesn't exist)
        $jobtype = Jobtype::findOrFail($id);

        // Before deleting, check if this job type is in use in any existing schedule
        if (  ScheduleEntry::where('jbtyp_id', '=', $jobtype->id)->count() > 0  ) {
            // CASE 1: job type still in use - let the user decide what to do in each case    
            
            // Inform the user about the redirect and go to detailed info about the job type selected
            Session::put('message', 'Diensttyp wurde NICHT gelöscht, weil er noch im Einsatz ist. Hier kannst du es ändern.');
            Session::put('msgType', 'danger');
            return Redirect::action( 'JobtypeController@show', ['id' => $jobtype->id] );
        } 
        else 
        {
            // CASE 2: job type is not used anywhere and can be remove without side effects
            
            // Log the action while we still have the data
            Log::info('Jobtype deleted: ' . 
                      Session::get('userName') . ' (' . Session::get('userId') . ', ' . Session::get('userGroup') . 
                      ') deleted "' . $jobtype->jbtyp_title .  '" (it was not used in any schedule).');

            // Now delete the jobtype
            Jobtype::destroy($id);

            // Return to the management page
            Session::put('message', 'Diensttyp wurde erfolgreich gelöscht.');
            Session::put('msgType', 'success');
            return Redirect::action( 'JobtypeController@index' );
        }
    }
}
