<?php
namespace Lara;

use Auth;
use DateTime;
use Illuminate\Database\Eloquent\Model;

use Request;

class Logging
{
    public static function logShiftRevision(Shift $shift, $action, $old = "", $new = "")
    {
        if (is_null($shift->schedule)) {
            return;
        }
        self::ensureShiftHasRevisions($shift);

        $schedule = $shift->schedule;
        $revisions = json_decode($schedule->entry_revisions);

        $newRevision = self::newShiftRevision($shift, $action, $old, $new);
        array_push($revisions, $newRevision);

        $schedule->entry_revisions = json_encode($revisions);

        $schedule->save();
    }

    public static function logScheduleRevision(Schedule $schedule, $action, $old = "" , $new = "")
    {
        self::ensureScheduleHasRevisions($schedule);

        $revisions = json_decode($schedule->entry_revisions);
        if(is_null($revisions)) {
            $revisions = [];
        }

        $newRevision = self::newScheduleRevision($schedule, $action, $old, $new);
        array_push($revisions, $newRevision);

        $schedule->entry_revisions = json_encode($revisions);
    }

    public static function logEventRevision(ClubEvent $event, $action, $old = "", $new = "")
    {
        if ($event->schedule) {
            self::logScheduleRevision($event->schedule, $action, $old, $new);
            $event->schedule->save();
        }
    }

    public static function ensureShiftHasRevisions(Shift $shift)
    {
        self::ensureScheduleHasRevisions($shift->schedule);
    }

    public static function ensureScheduleHasRevisions(Schedule $schedule)
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

        if ($schedule->entry_revisions === "" || $schedule->entry_revisions === null) {
            $schedule->entry_revisions = json_encode($defaultRevision);
            $schedule->save();
        }
    }

    public static function newShiftRevision($shift, $action, $old = "", $new = "")
    {
        $user = Auth::user();

        $person = new Person;

        if ($user) {
            $person = $user->person;
        }


        return [
            "entry id" => is_null($shift) ?  "" : $shift->id,
            "job type" => is_null($shift) ? "" : is_null($shift->shifttype_id) ? "" : ShiftType::find($shift->shifttype_id)->title(),
            "action" => $action,
            "old value" => $old,
            "new value" => $new,
            "user id" => $person->prsn_ldap_id != NULL ? $person->prsn_ldap_id : "",
            "user name" => $person->prsn_ldap_id != NULL ? $user->name . ' (' . $person->club->clb_title . ')' : "Gast",
            "from ip" => Request::getClientIp(),
            "timestamp" => (new DateTime)->format('d.m.Y H:i:s')
        ];
    }

    public static function newScheduleRevision($schedule, $action, $old = "", $new = "")
    {
        $user = Auth::user();

        if ($user) {
            $person = $user->person;
            return [
                "entry id" => is_null($schedule) ? "" : $schedule->id,
                "job type" => "",
                "action" => $action,
                "old value" => $old,
                "new value" => $new,
                "user id" => $person->prsn_ldap_id != NULL ? $person->prsn_ldap_id : "",
                "user name" => $person->prsn_ldap_id != NULL ? $user->name . ' (' . $person->club->clb_title . ')' : "Gast",
                "from ip" => Request::getClientIp(),
                "timestamp" => (new DateTime)->format('d.m.Y H:i:s')
            ];
        }
        // If no one is logged in, the event is created from an import or other automated tasks => Use Lara as creator
        return [
            "entry id" => is_null($schedule) ? "" : $schedule->id,
            "job type" => "",
            "action" => $action,
            "old value" => $old,
            "new value" => $new,
            "user id" => "",
            "user name" => "Lara",
            "from ip" => "",
            "timestamp" => (new DateTime)->format('d.m.Y H:i:s')
        ];
    }

    public static function scheduleCreated($schedule)
    {
        $schedule->entry_revisions = json_encode([self::newScheduleRevision(null, "revisions.eventCreated")]);
    }

    public static function commentChanged($shift, $old, $new)
    {
        if ($old !== $new ) {
            self::logShiftRevision($shift, "revisions.commentChanged", $old, $new);
        }
    }

    public static function commentAdded($shift, $comment)
    {
        self::logShiftRevision($shift, "revisions.commentAdded", "", $comment);
    }

    public static function commentDeleted($shift, $old)
    {
        self::logShiftRevision($shift, "revisions.commentDeleted", $old, "");
    }

    public static function shiftChanged(Shift $shift, $oldPerson, $newPerson)
    {
        if (is_null($oldPerson)) {
            if (!is_null($newPerson)) {
                self::logShiftRevision($shift, "revisions.shiftSignedIn", "", $newPerson->nameWithStatus());
            }
        } else {
            if (!is_null($newPerson)) {
                self::logShiftRevision($shift, "revisions.shiftChanged", $oldPerson->nameWithStatus(), $newPerson->nameWithStatus());
            } else {
                self::logShiftRevision($shift, "revisions.shiftSignedOut", $oldPerson->nameWithStatus(), "");
            }
        }
    }

    public static function shiftTypeChanged($shift, $oldShiftType, $newShiftType)
    {
        if (is_null($oldShiftType)) {
            if (!is_null($newShiftType)) {
                self::logShiftRevision($shift, "revisions.shiftRenamed", "", $newShiftType->title());
            }
        } else {
            if (!is_null($newShiftType)) {
                self::logShiftRevision($shift, "revisions.shiftRenamed", $oldShiftType->title(), $newShiftType->title());
            } else {
                self::logShiftRevision($shift, "revisions.shiftRenamed", $oldShiftType->title(), "");
            }
        }
    }

    public static function shiftCreated(Shift $shift)
    {
        self::logShiftRevision($shift, "revisions.shiftCreated");
    }

    public static function shiftDeleted(Shift $shift)
    {
        self::logShiftRevision($shift, "revisions.shiftDeleted");
    }

    public static function eventStartChanged($event)
    {
        self::attributeChanged($event, "evnt_time_start", "revisions.eventStartChanged");
    }

    public static function eventEndChanged($event)
    {
        self::attributeChanged($event, "evnt_time_end", "revisions.eventEndChanged");
    }

    public static function eventTitleChanged($event)
    {
        self::attributeChanged($event, "evnt_title", "revisions.eventTitleChanged");
    }

    public static function eventSubtitleChanged($event)
    {
        self::attributeChanged($event, "evnt_subtitle", "revisions.eventSubtitleChanged");
    }


    public static function shiftStatisticalWeightChanged($shift)
    {
        self::attributeChanged($shift, "statistical_weight", "revisions.shiftWeightChanged");
    }

    public static function shiftOptionalChanged($shift)
    {
        self::attributeChanged($shift, "optional", "revisions.shiftOptionalChanged");
    }

    public static function shiftStartChanged($shift)
    {
        self::attributeChanged($shift, "start", "revisions.shiftStartChanged");
    }

    public static function shiftEndChanged($shift)
    {
        self::attributeChanged($shift, "end", "revisions.shiftEndChanged");
    }

    public static function preparationTimeChanged($schedule) {
        self::attributeChanged($schedule, "schdl_time_preparation_start", "revisions.preparationStartChanged");
    }

    public static function attributeChanged($model, $attribute, $action)
    {
        $old = $model->getOriginal($attribute);
        $new = $model->$attribute;

        $logger = self::loggerForModel($model);
        if ($old != $new) {
            self::$logger($model, $action, $old, $new);
        }
    }

    public static function loggerForModel(Model $model)
    {
        if ($model instanceof ClubEvent) {
            return "logEventRevision";
        }
        if ($model instanceof Shift) {
            return "logShiftRevision";
        }
        if ($model instanceof Schedule) {
            return "logScheduleRevision";
        }
    }
}
