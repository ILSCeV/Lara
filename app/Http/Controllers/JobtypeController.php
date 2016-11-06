<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;

use Lara\Http\Requests;
use Lara\Http\Controllers\Controller;
use Lara\Jobtype;
use Session;
use Log;
use Redirect;

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
        // Get all the data
        $jobtype = Jobtype::findOrFail($id);
        
        // Check if jobtype exists
        if ( is_null($jobtype) ) {
            Session::put('message', "does not exist");
            Session::put('msgType', 'danger');
            return Redirect::back();
        }

        // Check credentials: you can only delete, if you have rights for marketing or management 
        if(!Session::has('userId') 
            OR (Session::get('userGroup') != 'marketing'
                AND Session::get('userGroup') != 'clubleitung'
                AND Session::get('userGroup') != 'admin'))
        {
            Session::put('message', 'Du darfst das nicht einfach löschen! Frage die Clubleitung oder Markleting ;)');
            Session::put('msgType', 'danger');
            return Redirect::back();
        }

        // Log the action while we still have the data
        Log::info('Jobtype deleted: ' . Session::get('userName') . ' (' . Session::get('userId') . ', ' 
                 . Session::get('userGroup') . ') deleted Jobtype "' . $jobtype->jbtyp_title .  '".');
        
        // HERE FIND ALL PLACES AND DO CLEAN REFERENCES!

        // Now delete the jobtype
        Jobtype::destroy($id);

        // show current month afterwards
        Session::put('message', 'Diensttyp wurde erfolgreich gelöscht.');
        Session::put('msgType', 'success');
        return Redirect::back();
    }
}
