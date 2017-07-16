<?php

namespace Lara\Http\Controllers;

use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Input;
use Session;
use Lara\Logging;

use Lara\Club;
use Lara\Person;
use Lara\Shift;
use Lara\ShiftType;
use Lara\Utilities;

class ShiftController extends Controller
{
    /**
     * Display the specified resource.
     * Returns JSON-formated contents of a shift.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shift = Shift::where('id', '=', $id)
            ->with('type', 'getPerson.getClub')
            ->firstOrFail();

        // Person NULL means "=FREI=" - check for it every time you query a relationship
        $ldapId = !is_null($shift->getPerson) ? $shift->getPerson->prsn_ldap_id : "";
        $name = !is_null($shift->getPerson) ? $shift->getPerson->prsn_name : "=FREI=";
        $status = !is_null($shift->getPerson) ? $shift->getPerson->prsn_status : "";
        $clubTitle = !is_null($shift->getPerson) ? $shift->getPerson->getClub->clb_title : "";
        $isCurrentUser = $ldapId == Session::get('userId');
        $response = [
            'id'                => $shift->id,
            'jbtyp_title'       => $shift->type->title(),
            'prsn_name'         => $name,
            'prsn_ldap_id'      => $ldapId,
            'prsn_status'       => $status,
            'clb_title'         => $clubTitle,
            'comment'=> $shift->comment,
            'start'  => $shift->start,
            'end'    => $shift->end,
            'updated_at'        => $shift->updated_at,
            'is_current_user'   => $isCurrentUser
        ];

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     * Changes contents of the shift specified by ID to contents in the REQUEST
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

        // If we only want to modify the shiftType via management pages - do it without evaluating the rest
        if ( !empty($request->get('shiftTypeId')) && is_numeric($request->get('shiftTypeId')) ) {

            // Find the corresponding shift object
            $shift = Shift::where('id', '=', $request->get('entryId'))
                ->first();

            // Substitute values
            $shift->shifttype_id = $request->get('shiftTypeId');
            $shift->save();

            // Log changes
            Logging::logShiftRevision($shift, "revisions.shiftTypeChanged");

            // Formulate the response
            return response()->json([
                "entryId" => $shift->id,
                "updatedShiftTypeTitle" => ShiftType::where('id', '=', $shift->shifttype_id)->first()->jbtyp_title
            ]);
        }

        // Extract request data
        $shiftId     = $request->get('entryId');
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

        // Find the corresponding shift object
        $shift = Shift::where('id', '=', $shiftId)->first();

        // Remember old value for logging
        $oldPerson = $shift->getPerson;
        $oldComment = $shift->comment;

        // Check if that schedule needs a password and validate hashes
        if ($shift->getSchedule->schdl_password !== ''
            && !Hash::check( $password, $shift->getSchedule->schdl_password ) ) {
            return response()->json("Fehler: das angegebene Passwort ist falsch, keine Änderungen wurden gespeichert. Bitte versuche erneut oder frage einen anderen Mitglied oder CL.", 401);
        }

        // FYI:
        // We separate schedule shift person change from comment change
        // because we need an option to add a comment to an empty field.
        // Deleting or editing the person doesn't delete the comment.

        // Check for person change (we will check for comment change later):
        //
        // Case EMPTY:     Entry was empty, shift is empty now              -> do nothing
        // Case SAME:      Entry was not empty, but same person is there    -> do nothing
        // Case ADDED:     Entry was empty, new data entered                -> add new data
        // Case DELETED:   Entry was not empty, shift is empty now          -> delete old data
        // Case CHANGED:   Entry was not empty, new name entered            -> delete old data, then add new data


        if( !isset($shift->person_id) )
        {
            if ( !$userName == '' )
            {
                // Case ADDED:   Shift was empty, new data entered -> add new data
                $this->onAdd($shift, $userName, $ldapId, $userClub);
                Logging::shiftChanged($shift, $oldPerson, $shift->person);
            }
        }
        else
        {
            if ( $userName == '' )
            {
                // Case DELETED: Shift was not empty, shift is empty now -> delete old data
                $this->onDelete($shift);
                Logging::shiftChanged($shift, $oldPerson, $shift->person);
            }
            else
            {
                // Differentiate between shifts with members or with guests
                if ( !is_null($shift->getPerson()->first()->prsn_ldap_id) )
                {
                    // Member shifts (with LDAP ID provided) shouldn't change club id, so no need to do anything in that case either
                    if ( $shift->getPerson->prsn_name == $userName
                        AND  Person::where('id', '=', $shift->person_id)->first()->prsn_ldap_id == $ldapId )
                    {
                        // Possibility 1: same name, same ldap = same person
                        // Case SAME: Shift was not empty, but same person is there -> do nothing
                    }
                    else {
                        // Possibility 2: same name, new/empty ldap  = another person
                        // Possibility 3: new name,  same ldap       = probably LDAP ID not cleared on save, assume another person
                        // Possibility 4: new name,  new/empty ldap  = another person
                        // Case CHANGED: Shift was not empty, new data entered -> delete old data, then add new data
                        $this->onDelete($shift);
                        $this->onAdd($shift, $userName, $ldapId, $userClub);
                        Logging::shiftChanged($shift, $oldPerson, $shift->person);
                    }
                }
                else
                {
                    // Guest shifts may change club
                    if ( $shift->getPerson->prsn_name == $userName
                        AND  $shift->getPerson->getClub->clb_title == $userClub
                        AND  $ldapId == '' )
                    {
                        // Possibility 1: same name, same club, empty ldap  = do nothing
                        // Case SAME: Shift was not empty, but same person is there -> do nothing
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
                        // Case NAME CHANGED: Shift was not empty, new data entered -> delete old data, then add new data
                        $this->onDelete($shift);
                        $this->onAdd($shift, $userName, $ldapId, $userClub);

                        Logging::shiftChanged($shift, $oldPerson, $shift->person);
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
        if( empty($shift->comment) )
        {
            if ( !$userComment == '' )
            {
                // Case ADDED: Comment was empty, new comment entered -> add new data
                $shift->comment = $userComment;
                $shift->save();
                Logging::commentAdded($shift, $userComment);
            }
            else
            {
                //Case EMPTY: Comment was empty, comment is empty now -> do nothing
            }
        }
        else
        {
            if( $shift->comment !== $userComment )
            {
                if ( $userComment == '' )
                {
                    // Case DELETED: Comment was not empty, comment is empty now -> delete old data
                    $shift->comment = null;
                    $shift->save();
                    Logging::commentDeleted($shift, $oldComment);
                }
                else
                {
                    // Case CHANGED: Comment was not empty, new comment entered -> delete old data, then add new data
                    $shift->comment = $userComment;
                    $shift->save();
                    Logging::commentChanged($shift, $oldComment, $userComment);
                }
            }
        }

        // Find user status icon parameters to return
        $userStatus = $this->updateStatus($shift);

        // Formulate the response
        $prsn_ldap_id = is_null($shift->getPerson()->first()) ? "" : $shift->getPerson()->first()->prsn_ldap_id;
        return response()->json([
            "entryId"           => $shift->id,
            "userStatus"        => $userStatus,
            "userName"          => is_null( $shift->getPerson()->first() ) ? "" : $shift->getPerson()->first()->prsn_name,
            "ldapId"            => $prsn_ldap_id,
            "userClub"          => is_null( $shift->getPerson()->first() ) ? "" : $shift->getPerson()->first()->getClub->clb_title,
            "userComment"       => $shift->comment,
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
        $shift = Shift::find($id);
        self::delete($shift);
    }

    /**
     * Deletes the shift from the database
     * @param \Lara\Shift $shift
     */
    public static function delete($shift) {

        // Check if shift exists
        if ( is_null( $shift ) ) {
            Session::put('message', 'Fehler: Löschvorgang abgebrochen - der Dienstplaneintrag existiert nicht.');
            Session::put('msgType', 'danger');
            return Redirect::back();
        }
        Utilities::clearIcalCache();

        // Delete the shift
        $shift->delete();
    }

    /**
     * @param $schedule
     * @param $isNewEvent
     * @param $title
     * @param $id
     * @param $type
     * @param $start
     * @param $end
     * @param $weight
     * @param $position
     */
    public static function makeShift($schedule, $isNewEvent, $title, $id, $type, $start, $end, $weight, $position)
    {
        if ($title === "") {
            return;
        }
        // If no id is set, create a new Model
        $shift = Shift::firstOrNew(["id" => $id]);

        // If there was a shifttype passed and one with the correct title exists, use this one
        // Otherwise create a new model
        $oldShiftType = $shift->type;

        // we need a raw statement for case sensitivity
        $shiftType = ShiftType::whereRaw("BINARY `jbtyp_title`= ?", $title)
            ->where(function($query) use($type, $start, $end){
                $query->where('id', $type);
                $query->orWhere('jbtyp_time_start', $start);
                $query->where('jbtyp_time_end', $end);
            })
            ->first();
        if (is_null($shiftType)) {
            $shiftType = new ShiftType([
                "id" => $type,
                "jbtyp_title" => $title,
                'jbtyp_time_start' => $start,
                'jbtyp_time_end' => $end,
                'jbtyp_statistical_weight' => $weight
            ]);
            $shiftType->save();
        }

        // if the model was newly created, save the new shiftType
        $shift->fill([
            "schedule_id" => $schedule->id,
            "start" => $start,
            "end" => $end,
            "statistical_weight" => $weight,
            "shifttype_id" => $shiftType->id,
            "position" => $position
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
        else if (!$isNewEvent) {
            $shift->save();
            Logging::shiftCreated($shift);
        }
        $shift->save();
    }



//--------- PRIVATE FUNCTIONS ------------



    /**
     * Deletes a shift.
     * Deletes the dataset in table Person if it's a guest (LDAP id = NULL), but doesn't touch club members.
     *
     * @param  Shift $shift
     * @return void
     */
    private function onDelete($shift)
    {
        if ( !isset($shift->getPerson->prsn_ldap_id) )
        {
            Person::destroy($shift->person_id);
        }

        $shift->person_id = null;
        $shift->save();
    }


    /**
     * Adds new person to the shift.
     *
     * @param  Shift $shift
     * @param  String $userName
     * @param  int $ldapId
     * @param  String $userClub
     * @return void
     */
    private function onAdd($shift, $userName, $ldapId, $userClub)
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

        // Save changes to person and schedule shift
        $person->updated_at = Carbon::now();
        $person->save();

        $shift->person_id = $person->id;
        $shift->save();
    }


    /**
     * Checks what kind of person occupies shift after changes and sets the status
     * to "free" or a person userStatus accordingly
     *
     * @param  Shift $shift
     * @return array $userStatus
     */
    private function updateStatus($shift) {
        if ( !is_null($shift->person_id) ) {
            switch (Person::where("id","=",$shift->person_id)->first()->prsn_status) {
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
                case 'guest':
                    $userStatus = ["status"=>"fa fa-times-circle-o", "style"=>"color:yellowgreen;", "title"=>"ex-Kandidat"];
                    break;
                case "":
                    $userStatus = ["status"=>"fa fa-circle-o", "style"=>"color:yellowgreen;", "title"=>"Extern"]; 
                    break;
            }
        }
        else
        {
            $userStatus = ["status"=>"fa fa-question", "style"=>"color:lightgrey;", "title"=>"Dienst frei"]; 
        }

        return $userStatus;
    }



}
