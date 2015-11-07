<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;

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

        return response()->json($response);
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Changes contents of the entry specified by ID to contents in the REQUEST
        // IMPLEMENT LATER
        // VALIDATE PROPER REQUEST FORM
        $content = "";
        $status = 501;  // "Not implemented"
        return response($content, $status);
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
