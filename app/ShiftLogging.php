<?php

namespace Lara;

use Lara\Shift;


class ShiftLogging {

    private $changedAttributes;
    private $oldAttributes;
    private $newAttributes;

    private $schedule;
    private $shift;

    private $isNewShift;

    public function __construct(Shift $modifiedShift)
    {
        $this->shift = $modifiedShift;

        $this->changedAttributes = collect($modifiedShift->getDirty());
        $this->oldAttributes = collect($modifiedShift->getOriginal());
        $this->newAttributes = collect($modifiedShift->getAttributes());

        $this->schedule = $modifiedShift->schedule;

        $this->isNewShift = $modifiedShift->exists;
    }

    public function logAllChanges()
    {

    }

    private function logRename()
    {
        $wasShiftTypeChanged = $this->hasAttributeChanged('shiftttype_id');

        $oldShift = ShiftType::find($this->oldAttributes->get('shifttype_id'));
        $newShift = ShiftType::find($this->newAttributes->get('shifttype_id'));

        $hasNameChanged = $oldShift->jbtyp_title !== $newShift->jbtyp_title;

        if ($hasNameChanged) {

        }
    }

    private function hasAttributeChanged($attribute)
    {
        return $this->changedAttributes->has($attribute);
    }

    private function logChange()
    {
        if($this->$schedule->entry_revisions == "") {
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

        $newRevision = [
            "entry id" => $this->$shift->id,
            "job type" => $this->$shift->type->title(),
            "action" => $action,
            "old id" => $oldId,
            "old value" => $oldName,
            "old comment" => $oldComment,
            "new id" => $newId,
            "new value" => $newName,
            "new comment" => $newComment,
            "user id" => Session::get('userId') != NULL ? Session::get('userId') : "",
            "user name" => Session::get('userId') != NULL ? Session::get('userName') . ' (' . Session::get('userClub') . ')' : "Gast",
            "from ip" => Request::getClientIp(),
            "timestamp" => (new DateTime)->format('d.m.Y H:i:s')
        ];
        // append current change
        array_push($revisions, $newRevision);

        // encode and save
        $schedule->entry_revisions = json_encode($revisions);

        $schedule->save();
    }

    private function makeRevisionEntry()
    {
        return [
            "entry id" => $this->$shift->id,
            "job type" => $this->$shift->type->title(),
            "action" => $action,
            "old id" => $oldId,
            "old value" => $oldName,
            "old comment" => $oldComment,
            "new id" => $newId,
            "new value" => $newName,
            "new comment" => $newComment,
            "user id" => Session::get('userId') != NULL ? Session::get('userId') : "",
            "user name" => Session::get('userId') != NULL ? Session::get('userName') . ' (' . Session::get('userClub') . ')' : "Gast",
            "from ip" => Request::getClientIp(),
            "timestamp" => (new DateTime)->format('d.m.Y H:i:s')
        ];
    }

}
