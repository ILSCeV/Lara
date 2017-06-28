<?php
namespace Lara;
use DateTime;
use Session;
use Request;

class Logging
{
    public static function logRevision(Shift $shift, $action, $old = "", $new = "")
    {
        self::ensureShiftHasRevisions($shift);

        $schedule = $shift->schedule;
        $revisions = json_decode($schedule->entry_revisions);

        $newRevision = [
            "entry id" => $shift->id,
            "job type" => $shift->type->title(),
            "action" => $action,
            "old value" => $old,
            "new value" => $new,
            "user id" => Session::get('userId') != NULL ? Session::get('userId') : "",
            "user name" => Session::get('userId') != NULL ? Session::get('userName') . ' (' . Session::get('userClub') . ')' : "Gast",
            "from ip" => Request::getClientIp(),
            "timestamp" => (new DateTime)->format('d.m.Y H:i:s')
        ];
        array_push($revisions, $newRevision);

        $schedule->entry_revisions = json_encode($revisions);

        $schedule->save();
    }

    public static function ensureShiftHasRevisions(Shift $shift)
    {
        $defaultRevision = [
            [
                "entry id" => "",
                "job type" => "",
                "action" => "revisions.noOlderChanges",
                "old value" => "",
                "new value" => "",
                "timestamp" => (new DateTime)->format('d.m.Y H:i:s'),
                "user name" => "",
                "user id" => ""
            ]
        ];

        $schedule = $shift->schedule;

        if ($schedule->revisions === "" || $schedule->revisions === null) {
            $schedule->revisions = json_encode($defaultRevision);
            $schedule->save();
        }
    }


    public static function commentChanged($shift, $old, $new)
    {
        if ($old !== $new ) {
            self::logRevision($shift, "revisions.commentChanged", $old, $new);
        }
    }

    public static function commentAdded($shift, $comment)
    {
        self::logRevision($shift, "revisions.commentAdded", "", $comment);
    }

    public static function commentDeleted($shift, $old)
    {
        self::logRevision($shift, "revisions.commentDeleted", $old, "");
    }

    public static function shiftChanged(Shift $shift, $oldPerson, $newPerson)
    {
        if (is_null($oldPerson)) {
            if (!is_null($newPerson)) {
                self::logRevision($shift, "revisions.shiftSignedIn", "", $newPerson->nameWithStatus());
            }
        } else {
            if (!is_null($newPerson)) {
                self::logRevision($shift, "revisions.shiftChanged", $oldPerson->nameWithStatus(), $newPerson->nameWithStatus());
            } else {
                self::logRevision($shift, "revisions.shiftSignedOut", $oldPerson->nameWithStatus(), "");
            }
        }
    }

    public static function shiftTypeChanged($shift, $oldShiftType, $newShiftType)
    {
        if (is_null($oldShiftType)) {
            if (!is_null($newShiftType)) {
                self::logRevision($shift, "revisions.shiftRenamed", "", $newShiftType->title());
            }
        } else {
            if (!is_null($newShiftType)) {
                self::logRevision($shift, "revisions.shiftRenamed", $oldShiftType->title(), $newShiftType->title());
            } else {
                self::logRevision($shift, "revisions.shiftRenamed", $oldShiftType->title(), "");
            }
        }
    }

    public static function shiftCreated(Shift $shift)
    {
        self::logRevision($shift, "revisions.shiftCreated");
    }

    public static function shiftDeleted(Shift $shift)
    {
        self::logRevision($shift, "revisions.shiftDeleted");
    }
}
