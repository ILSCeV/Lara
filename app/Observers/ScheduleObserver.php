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


    }


    public function created(Schedule $schedule) 
    {
        Logging::scheduleCreated($schedule);
        $schedule->save();
    }

    public function updating(Schedule $schedule) 
    {
        $dirty = collect($schedule->getDirty());
        // entry-revisons should not count to modifying the event
        // they could also be caused by changing shifts
        $dirty->pull('entry_revisions');

        $user = Auth::user();

        if ($dirty->has('schdl_time_preparation_start')) {
            Logging::preparationTimeChanged($schedule);
        }

        if ($user && $dirty->isNotEmpty()) {
            $event = $schedule->event;

            $event->was_manually_edited = true;

            $event->save();
        }

    }
}
