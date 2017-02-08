<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;
use Lara\Utilities;
use Session;
use Input;

use Carbon\Carbon;

use Lara\Http\Requests;
use Lara\Http\Controllers\Controller;

use Hash;

use Lara\ScheduleEntry;
use Lara\Jobtype;
use Lara\Person;
use Lara\Club;
use Log;


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
        $ldapId = !is_null($entry->getPerson) ? $entry->getPerson->prsn_ldap_id : "";
        $response = [
            'id'                => $entry->id,
            'jbtyp_title'       => $entry->getJobType->jbtyp_title,
            'prsn_name'         => !is_null($entry->getPerson) ? $entry->getPerson->prsn_name          : "=FREI=",
            'prsn_ldap_id'      => $ldapId,
            'prsn_status'       => !is_null($entry->getPerson) ? $entry->getPerson->prsn_status        : "",
            'clb_title'         => !is_null($entry->getPerson) ? $entry->getPerson->getClub->clb_title : "",
            'entry_user_comment'=> $entry->entry_user_comment,
            'entry_time_start'  => $entry->entry_time_start,
            'entry_time_end'    => $entry->entry_time_end,
            'updated_at'        => $entry->updated_at,
            'is_current_user'   => $ldapId == Session::get('userId')
        ];

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
        // Check if it's our form (CSRF protection)
        if ( Session::token() !== Input::get( '_token' ) ) {
            return response()->json('Fehler: die Session ist abgelaufen. Bitte aktualisiere die Seite und logge dich ggf. erneut ein.', 401);
        }

        Utilities::clearIcalCache();

        // If we only want to modify the jobtype via management pages - do it without evaluating the rest
        if ( !empty($request->get('jobtypeId')) AND is_numeric($request->get('jobtypeId')) ) {

            // Find the corresponding entry object 
            $entry = ScheduleEntry::where('id', '=', $request->get('entryId'))->first();


            

            // Save old value for revision
            $oldJobtype = Jobtype::where('id', '=', $entry->jbtyp_id)->first()->jbtyp_title;

            // Substitute values
            $entry->jbtyp_id = $request->get('jobtypeId');
            $entry->save();

            // Log changes 
            ScheduleController::logRevision($entry->getSchedule,    // schedule object
                                $entry,                             // entry object
                                "Diensttyp geändert",               // action description
                                null,                               // old value - TODO LATER
                                null,                               // new value - TODO LATER
                                null,                               // old comment - no change here
                                null);                              // new comment - no change here

            // Formulate the response
            return response()->json(["entryId" => $entry->id, 
                                     "updatedJobtypeTitle" => Jobtype::where('id', '=', $entry->jbtyp_id)->first()->jbtyp_title], 
                                     200);
        }
        
        // Extract request data 
        $entryId     = $request->get('entryId');
        $userName    = $request->get('userName');
        $ldapId      = $request->get('ldapId');
        $timestamp   = $request->get('timestamp');
        $userClub    = $request->get('userClub');
        $userComment = $request->get('userComment');
        $password    = $request->get('password');

        // Check if someone modified LDAP ID manually
        if ( !empty($ldapId) AND !is_numeric($ldapId) ) {
            return response()->json("Fehler: die Clubnummer wurde in falschem Format angegeben. Bitte versuche erneut oder melde diesen Fehler dem Admin.", 400);
        }

        // Find the corresponding entry object 
        $entry = ScheduleEntry::where('id', '=', $entryId)->first();

        // Remember old value for logging
        $oldPerson = $entry->getPerson;
        $oldComment = $entry->entry_user_comment;

        // Check if that schedule needs a password and validate hashes
        if ($entry->getSchedule->schdl_password !== ''
            && !Hash::check( $password, $entry->getSchedule->schdl_password ) ) {
                return response()->json("Fehler: das angegebene Passwort ist falsch, keine Änderungen wurden gespeichert. Bitte versuche erneut oder frage einen anderen Mitglied oder CL.", 401);
        }

        // FYI: 
        // We separate schedule entry person change from comment change
        // because we need an option to add a comment to an empty field.
        // Deleting or editing the person doesn't delete the comment.

        // Check for person change (we will check for comment change later):
        //
        // Case EMPTY:     Entry was empty, entry is empty now              -> do nothing
        // Case SAME:      Entry was not empty, but same person is there    -> do nothing
        // Case ADDED:     Entry was empty, new data entered                -> add new data
        // Case DELETED:   Entry was not empty, entry is empty now          -> delete old data
        // Case CHANGED:   Entry was not empty, new name entered            -> delete old data, then add new data
     
        if( !isset($entry->prsn_id) )
        {
            if ( !$userName == '' )
            {
                // Case ADDED:   Entry was empty, new data entered -> add new data
                $this->onAdd($entry, $userName, $ldapId, $userClub);
                ScheduleController::logRevision($entry->getSchedule,                // schedule object
                                                $entry,                             // entry object
                                                "Dienst eingetragen",               // action description
                                                null,                               // old value
                                                $entry->getPerson()->first(),       // new value 
                                                null,                               // old comment - no change here
                                                null);                              // new comment - no change here 
            }
            else
            {
                // Case EMPTY: Entry was empty, entry is empty now -> do nothing
            }
        }
        else
        {
            if ( $userName == '' )
            {
                // Case DELETED: Entry was not empty, entry is empty now -> delete old data
                $this->onDelete($entry);
                ScheduleController::logRevision($entry->getSchedule,                // schedule object
                                                $entry,                             // entry object
                                                "Dienst ausgetragen",               // action description
                                                $oldPerson,                         // old value
                                                null,                               // new value 
                                                null,                               // old comment - no change here
                                                null);                              // new comment - no change here 
            }
            else
            {
                // Differentiate between entries with members or with guests
                if ( !is_null($entry->getPerson()->first()->prsn_ldap_id) ) 
                {
                    // Member entries (with LDAP ID provided) shouldn't change club id, so no need to do anything in that case either
                    if ( $entry->getPerson->prsn_name == $userName 
                    AND  Person::where('id', '=', $entry->prsn_id)->first()->prsn_ldap_id == $ldapId ) 
                    {
                        // Possibility 1: same name, same ldap = same person
                        // Case SAME: Entry was not empty, but same person is there -> do nothing
                    }
                    else 
                    {
                        // Possibility 2: same name, new/empty ldap  = another person 
                        // Possibility 3: new name,  same ldap       = probably LDAP ID not cleared on save, assume another person
                        // Possibility 4: new name,  new/empty ldap  = another person 
                        // Case CHANGED: Entry was not empty, new data entered -> delete old data, then add new data           
                        $this->onDelete($entry);
                        $this->onAdd($entry, $userName, $ldapId, $userClub);
                        ScheduleController::logRevision($entry->getSchedule,                // schedule object
                                                        $entry,                             // entry object
                                                        "Dienst geändert",                  // action description
                                                        $oldPerson,                         // old value
                                                        $entry->getPerson()->first(),       // new value 
                                                        null,                               // old comment - no change here
                                                        null);                              // new comment - no change here
                    }
                } 
                else
                {
                    // Guest entries may change club
                    if ( $entry->getPerson->prsn_name == $userName 
                    AND  $entry->getPerson->getClub->clb_title == $userClub
                    AND  $ldapId == '' ) 
                    {
                        // Possibility 1: same name, same club, empty ldap  = do nothing
                        // Case SAME: Entry was not empty, but same person is there -> do nothing
                    } 
                    else
                    {
                        // Possibility 2: same name, new club,  empty ldap  -> Case CHANGED
                        // Possibility 3: same name, same club, new ldap    -> Case CHANGED
                        // Possibility 4: same name, new club,  new ldap    -> Case CHANGED
                        // Possibility 5: new name,  same club, empty ldap  -> Case CHANGED
                        // Possibility 6: new name,  new club,  empty ldap  -> Case CHANGED
                        // Possibility 7: new name,  same club, new ldap    -> Case CHANGED
                        // Possibility 8: new name,  new club,  new ldap    -> Case CHANGED
                        // Case NAME CHANGED: Entry was not empty, new data entered -> delete old data, then add new data           
                        $this->onDelete($entry);
                        $this->onAdd($entry, $userName, $ldapId, $userClub);
                        ScheduleController::logRevision($entry->getSchedule,                // schedule object
                                                        $entry,                             // entry object
                                                        "Dienst geändert",                  // action description
                                                        $oldPerson,                         // old value
                                                        $entry->getPerson()->first(),       // new value 
                                                        null,                               // old comment - no change here
                                                        null);                              // new comment - no change here 
                    }  
                }
            }
        }
    
        // Now let's check for comment changes:
        //
        // Case EMPTY:   Comment was empty, comment is empty now                -> do nothing
        // Case SAME:    Comment was not empty, but same comment is there       -> do nothing
        // Case ADDED:   Comment was empty, new comment entered                 -> add new data
        // Case DELETED: Comment was not empty, comment is empty now            -> delete old data
        // Case CHANGED: Comment was not empty, new comment entered             -> delete old data, then add new data        
        if( empty($entry->entry_user_comment) )
        {
            if ( !$userComment == '' )
            {
                // Case ADDED: Comment was empty, new comment entered -> add new data
                $entry->entry_user_comment = $userComment;
                $entry->save(); 
                ScheduleController::logRevision($entry->getSchedule,                    // schedule object
                                                    $entry,                             // entry object
                                                    "Kommentar hinzugefügt",            // action description
                                                    null,                               // old value (no need to log no change)
                                                    null,                               // new value (no need to log no change)
                                                    null,                               // old comment
                                                    $userComment);                      // new comment 
            }
            else
            {
                //Case EMPTY: Comment was empty, comment is empty now -> do nothing
            }
        }
        else
        {
            if( $entry->entry_user_comment == $userComment )
            {
                // Case SAME: Comment was not empty, but same comment is there -> do nothing
            }
            else
            { 
                if ( $userComment == '' )
                {
                    // Case DELETED: Comment was not empty, comment is empty now -> delete old data
                    $entry->entry_user_comment = null;
                    $entry->save();
                    ScheduleController::logRevision($entry->getSchedule,                // schedule object
                                                    $entry,                             // entry object
                                                    "Kommentar gelöscht",               // action description
                                                    null,                               // old value (no need to log no change)
                                                    null,                               // new value (no need to log no change)
                                                    $oldComment,                        // old comment
                                                    null);                              // new comment
                }
                else
                {
                    // Case CHANGED: Comment was not empty, new comment entered -> delete old data, then add new data  
                    $entry->entry_user_comment = $userComment;
                    $entry->save();
                    ScheduleController::logRevision($entry->getSchedule,                // schedule object
                                                    $entry,                             // entry object
                                                    "Kommentar geändert",               // action description
                                                    null,                               // old value (no need to log no change)
                                                    null,                               // new value (no need to log no change)
                                                    $oldComment,                        // old comment
                                                    $userComment);                      // new comment
                }
            }
        }    

        // Find user status icon parameters to return
        $userStatus = $this->updateStatus($entry);        

        // Formulate the response
        $prsn_ldap_id = is_null($entry->getPerson()->first()) ? "" : $entry->getPerson()->first()->prsn_ldap_id;
        return response()->json([
            "entryId"           => $entry->id,
            "userStatus"        => $userStatus,
            "userName"          => is_null( $entry->getPerson()->first() ) ? "" : $entry->getPerson()->first()->prsn_name,
            "ldapId"            => $prsn_ldap_id,
            "userClub"          => is_null( $entry->getPerson()->first() ) ? "" : $entry->getPerson()->first()->getClub->clb_title,
            "userComment"       => $entry->entry_user_comment,
            "timestamp"         => $timestamp,
            "is_current_user"   => $prsn_ldap_id == Session::get('userId')
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     * Deletes the dataset in table Person if it's a guest (LDAP id = NULL), but doesn't touch club members.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy($id)
    {
        // Get all the data
        $entry = ScheduleEntry::find($id);

        // Check if entry exists
        if ( is_null( $entry ) ) {
            Session::put('message', 'Fehler: Löschvorgang abgebrochen - der Dienstplaneintrag existiert nicht.');
            Session::put('msgType', 'danger');
            return Redirect::back();
        }
        Utilities::clearIcalCache();

        // Delete the entry
        ScheduleEntry::destroy($id);
    }



//--------- PRIVATE FUNCTIONS ------------



    /**
     * Deletes a schedule entry. 
     * Deletes the dataset in table Person if it's a guest (LDAP id = NULL), but doesn't touch club members.
     *
     * @param  ScheduleEntry $entry
     * @return void
     */
    private function onDelete($entry)
    {
        if ( !isset($entry->getPerson->prsn_ldap_id) )
        {
            Person::destroy($entry->prsn_id);
        }

        $entry->prsn_id = null;
        $entry->save();
    }


    /**
     * Adds new person to the schedule entry.
     *
     * @param  ScheduleEntry $entry
     * @param  String $userName
     * @param  int $ldapId
     * @param  String $userClub
     * @return void
     */
    private function onAdd($entry, $userName, $ldapId, $userClub)
    {
        // If no LDAP id provided - create new GUEST person
        if ( $ldapId == '' )
        {
            $person = Person::create( array('prsn_ldap_id' => null) );
            $person->prsn_name = $userName;
            $person->prsn_status = "";
        }
        // Otherwise find existing MEMBER person in DB
        else
        {
            $person = Person::where('prsn_ldap_id', '=', $ldapId )->first();

            // If not found, then a user is adding own data for the first time.
            // Let's create a new person with data provided in the session.
            if (is_null($person))
            {
                $person = Person::create( array('prsn_ldap_id' => $ldapId) );
                $person->prsn_name = $userName;
                $person->prsn_status = Session::get('userStatus');
                $person->prsn_uid = hash("sha512", uniqid());
            }

            // If a person adds him/herself - update status from session to catch if it was changed in LDAP
            if ($person->prsn_ldap_id == Session::get('userId'))
            {
                $person->prsn_status = Session::get('userStatus');
                $person->prsn_name = Session::get('userName');
            }

        }

        // If club input is empty setting clubId to '-' (clubId 1).
        // Else - look for a match in the Clubs DB and set person->clubId = matched club's id.
        // No match found - creating a new club with title from input.
        if ( $userClub == '' OR $userClub == '-' )
        {
            $person->clb_id = '1';
        }
        else
        {
            $match = Club::firstOrCreate( array('clb_title' => $userClub) );
            $person->clb_id = $match->id;
        }

        // Save changes to person and schedule entry
        $person->updated_at = Carbon::now();
        $person->save();

        $entry->prsn_id = $person->id;
        $entry->save();
    }


    /**
     * Checks what kind of person occupies entry after changes and sets the status
     * to "free" or a person userStatus accordingly
     *
     * @param  ScheduleEntry $entry
     * @return array $userStatus
     */
    private function updateStatus($entry) {
        if ( !is_null($entry->prsn_id) ) {
            switch (Person::where("id","=",$entry->prsn_id)->first()->prsn_status) {
                case 'candidate':
                    $userStatus = ["status"=>"fa fa-adjust", "style"=>"color:yellowgreen;", "title"=>"Kandidat"];
                    break;
                case 'veteran':
                    $userStatus = ["status"=>"fa fa-star", "style"=>"color:gold;", "title"=>"Veteran"];
                    break;
                case 'member':
                    $userStatus = ["status"=>"fa fa-circle", "style"=>"color:forestgreen;", "title"=>"Aktiv"];
                    break;
                case 'resigned':
                    $userStatus = ["status"=>"fa fa-star-o", "style"=>"color:gold;", "title"=>"ex-Mitglied"];
                    break;
                case "":
                    $userStatus = ["status"=>"fa fa-circle", "style"=>"color:lightgrey;", "title"=>"Extern"];
                    break;
            }
        }
        else
        {
            $userStatus = ["status"=>"fa fa-circle-o", "style"=>"color:lightgrey;", "title"=>"Dienst frei"];
        } 

        return $userStatus;
    }

}
