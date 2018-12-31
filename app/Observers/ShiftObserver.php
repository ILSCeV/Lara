<?php

namespace Lara\Observers;

use Lara\Shift;
use Lara\utilities\CacheUtility;

class ShiftObserver
{
    /**
     * Handle the shift "created" event.
     *
     * @param  \Lara\Shift  $shift
     * @return void
     */
    public function created(Shift $shift)
    {
        //
    }

    /**
     * Handle the shift "updated" event.
     *
     * @param  \Lara\Shift  $shift
     * @return void
     */
    public function updated(Shift $shift)
    {
        //
        CacheUtility::forget();
    }

    /**
     * Handle the shift "deleted" event.
     *
     * @param  \Lara\Shift  $shift
     * @return void
     */
    public function deleted(Shift $shift)
    {
        //
        CacheUtility::forget();
    }

    /**
     * Handle the shift "restored" event.
     *
     * @param  \Lara\Shift  $shift
     * @return void
     */
    public function restored(Shift $shift)
    {
        //
    }

    /**
     * Handle the shift "force deleted" event.
     *
     * @param  \Lara\Shift  $shift
     * @return void
     */
    public function forceDeleted(Shift $shift)
    {
        //
    }
}
