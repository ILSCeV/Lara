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
        $this->classString = "";
        $this->calendarEvent();

        $this->pastEvent();
        $this->ownShift();
        $this->eventType();

       // $this->sectionsBackground();

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
            $classForType = $this->eventTypeToClass();
            $this->addClass($classForType);
        }
    }

    /**
     * @return string
     */
    public function eventTypeToClass()
    {
        $prefix = "calendar-";
        $prefix .= $this->event->evnt_is_private ? "internal-" : "public-";
        switch ($this->event->evnt_type) {
            case 1:
                return $prefix . "info";
            case 6:
                return $prefix . "task";
            case 7:
                return $prefix . "marketing";
            case 8:
                return $prefix . "marketing";
            case 9:
                return $prefix . "task";
            default:
                return "calendar-Event-" . $this->event->section->color;
        }
    }

    public function calendarEvent()
    {
        $this->addClass("cal-event");
    }

    public function sectionsBackground()
    {
        $relevantSections = $this->event->sectionsToShowTo();
        $amountOfSections = $relevantSections->count();
        $event = $this->event;
        $colors = $relevantSections->sortBy(function($section) use($event) {
            if ($section->id === $event->section->id) {
                return 0;
            }
            return $section->id;
        })
            ->map(function ($section) {
                return $section->color;
            })
            ->implode("-");
        $this->addClass("calendar-" . $amountOfSections . "-Event-" . $colors);
    }

}