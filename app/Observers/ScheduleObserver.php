<?php

namespace Lara\Observers;

use Lara\Schedule;
use Lara\Logging;

use Auth;

class ScheduleObserver
{
    /**
     * Listen to the Schedule saving event
     *
     * @param  Lara\Schedule  $schedule
     * @return void
     */
    public function saving(Schedule $schedule)
    {
        $user = Auth::user();

        $isDirty = $schedule->isDirty();

        if ($user && $isDirty) {
            $event = $schedule->event;

            $event->was_manually_edited = true;

            $event->save();
        }
    }

}
