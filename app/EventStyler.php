<?php

namespace Lara;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class EventStyler
{
    private $event;
    private $classString;

    public static function classesForEvent(ClubEvent $event)
    {
        $styler = new EventStyler($event);
        return $styler->eventClassString();
    }

    public function __construct(ClubEvent $event)
    {
        $this->event = $event;
        $this->classString = "";
    }

    public function eventClassString()
    {
        $this->calendarEvent();

        $this->pastEvent();
        $this->ownShift();
        $this->eventType();

        return $this->classString;
    }

    private function addClass($classToAdd)
    {
        if ($this->classString == "") {
            $this->classString = $classToAdd;
        }
        else {
            $this->classString .= " " . $classToAdd;
        }
    }

    public function pastEvent()
    {
        if (strtotime($this->event->evnt_date_end . ' ' . $this->event->evnt_time_end) < time()) {
            $this->addClass("past-eventOrSurvey");
        }
    }

    public function ownShift()
    {
        $isUserLoggedIn = Session::has('userId');
        $hasOwnShiftInEvent = $this->event->hasShift($this->event->getSchedule->id, Session::get('userId'));

        if ($isUserLoggedIn && $hasOwnShiftInEvent) {
            $this->addClass("cal-month-my-event");
        }
    }

    public function eventType()
    {
        $isGuest = !Session::has("userId");
        $isEventPrivate = $this->event->evnt_is_private;

        if ($isGuest && $isEventPrivate) {
            $this->addClass("dark-grey");
        }
        else {
            $classForType = "calendar-";
            $classForType .= $this->event->evnt_is_private ? "internal-" : "public-";
            $classForType .= $this->eventTypeToClass($classForType);
            $this->addClass($classForType);
        }
    }

    /**
     * @return string
     */
    public function eventTypeToClass()
    {
        switch ($this->event->evnt_type) {
            case 1:
                return "info";
            case 6:
                return "task";
            case 7:
                return "marketing";
            case 8:
                return "marketing";
            case 9:
                return "task";
            default:
                return "event-" . $this->event->section->title;
        }
    }

    public function calendarEvent()
    {
        $this->addClass("cal-event");
    }

}