<?php

namespace Lara\Observers;

use Auth;
use Lara\ClubEvent;

class ClubEventObserver
{
    /**
     * Listen to the ClubEvent saving event.
     * Could also put this into the 'updating' method.
     * This way, manually created events will also receive the 
     * 'was_manually_edited' flag, which seems consistent.
     *
     * @param  Lara\ClubEvent  $event
     * @return void
     */
    public function saving(ClubEvent $event)
    {
        $user = Auth::user();

        $isDirty = $event->isDirty();

        if ($user && $isDirty) {
            $event->was_manually_edited = true;
        }
    }

}
