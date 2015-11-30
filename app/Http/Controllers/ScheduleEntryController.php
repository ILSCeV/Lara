<?php

namespace Lara\Http\Controllers;

use Request;
use Session;
use Input;

use Lara\Http\Requests;
use Lara\Http\Controllers\Controller;

use Lara\ScheduleEntry;
use Lara\Jobtype;
use Lara\Person;


class ScheduleEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Not needed because single entries have no meaning without a schedule+event context
        // Restricted via routes exception.
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Not needed because entries are created only as part of a schedule+event pair. 
        // Restricted via routes exception.
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Called as part of SCHEDULE CREATE
        // IMPLEMENT LATER
        $content = "";
        $status = 501;  // "Not implemented"
        return response($content, $status);
    }

    /**
     * Display the specified resource.
     * Returns JSON-formated contents of a schedule entry.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $entry = ScheduleEntry::where('id', '=', $id)
                              ->with('getJobType',
                                     'getPerson.getClub')
                              ->firstOrFail();
                              
        // Person NULL means "=FREI=" - check for it every time you query a relationship
        $response = ['id'                => $entry->id, 
                     'jbtyp_title'       => $entry->getJobType->jbtyp_title,
                     'prsn_name'         => !is_null($entry->getPerson) ? $entry->getPerson->prsn_name          : "=FREI=",
                     'prsn_ldap_id'      => !is_null($entry->getPerson) ? $entry->getPerson->prsn_ldap_id       : "",
                     'prsn_status'       => !is_null($entry->getPerson) ? $entry->getPerson->prsn_status        : "",
                     'clb_title'         => !is_null($entry->getPerson) ? $entry->getPerson->getClub->clb_title : "",
                     'entry_user_comment'=> $entry->entry_user_comment,
                     'entry_time_start'  => $entry->entry_time_start,
                     'entry_time_end'    => $entry->entry_time_end,
                     'updated_at'        => $entry->updated_at];
                     

        if (Request::ajax()) {
            return response()->json($response);
        } else {     
            return response()->json($response);
            //return View::make('items.index');
        }
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Called as part of SCHEDULE CREATE
        // IMPLEMENT LATER
        $content = "";
        $status = 501;  // "Not implemented"
        return response($content, $status);
    }

    /**
     * Update the specified resource in storage.
     * Changes contents of the entry specified by ID to contents in the REQUEST
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Check if it's our form
        if ( Session::token() !== Input::get( '_token' ) ) {
            return response()->json('Error 401: Unauthorized attempt. Try reloading the page.', 401);
        }

        // Extract request data
        $entryId     = Input::get( 'entryId' );
        $userName    = Input::get( 'userName' );
        $ldapId      = Input::get( 'ldapId' );
        $userClub    = Input::get( 'userClub' );
        $userComment = Input::get( 'userComment' );


        // VALIDATE PROPER REQUEST FORM
        if ( !empty($ldapId) AND !is_numeric($ldapId) ) {
            return response()->json("Error: I don't know how, but you gave me a wrong LDAP ID. I will not tolerate such reckless behaviour.", 400);
        }

        if ( !empty($ldapId) AND is_null(Session::get('userId')) ) {
            return response()->json("Error: you tried to change an entry of a club member, but you are not logged in. Please log in or ask a club member to make changes for you.", 400);
        }

        /*
        if ( Person::where('id', '=', $ldapId)->first() !== Input::get('userName') ) {
            return response()->json("Error: you tried to save a person with a wrong LDAP ID. That I can't allow. Try reloading the page.", 400);
        }
        */
        
        //.....
        //validate data
        //and then store it in DB
        //.....
  

        // Formulate the responce
        
        $content = ["entryId"     => $entryId, 
                    "userName"    => $userName,
                    "ldapId"      => $ldapId, 
                    "userClub"    => $userClub,
                    "userComment" => $userComment];
        $status = 200;  
        return response()->json($content, $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Called as part of SCHEDULE UPDATE
        // IMPLEMENT LATER
        $content = "";
        $status = 501;  // "Not implemented"
        return response($content, $status);
    }
}
