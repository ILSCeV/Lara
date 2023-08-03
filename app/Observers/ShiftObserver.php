<?php

namespace Lara\Observers;

use Lara\utilities\CacheUtility;

use Lara\Shift;

class ShiftObserver
{
    /**
     * Listen to the Shift saved (create or update) event.
     *
     * @param  \Lara\Shift  $shift
     * @return void
     */
    public function saved(Shift $shift)
    {
        CacheUtility::forgetMonthTable($shift->getSchedule()->getRelated()->getClubEvent()->getRelated());
    }

     /**
     * Listen to the Shift deleted event.
     *
     * @param  \Lara\Shift  $shift
     * @return void
     */
    public function deleted(Shift $shift)
    {
        CacheUtility::forgetMonthTable($shift->getSchedule()->getRelated()->getClubEvent()->getRelated());
    }
}
