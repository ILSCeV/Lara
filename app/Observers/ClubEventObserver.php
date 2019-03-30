<?php

namespace Lara\Observers;

use Auth;
use Lara\utilities\CacheUtility;
use Log;

use Lara\ClubEvent;
use Lara\Logging;

class ClubEventObserver
{
    /**
     * Listen to the ClubEvent saving event.
     * Could also put this into the 'updating' method.
     * This way, manually created events will also receive the 
     * 'was_manually_edited' flag, which seems consistent.
     *
     * @param  \Lara\ClubEvent  $event
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

    public function created(ClubEvent $event) 
    {
        $user = Auth::user();

        if ($user) {
            Log::info('Event created: ' . $user->name . ' (' . $user->person->prsn_ldap_id . ', '
                . ') created event "' . $event->evnt_title . '" (eventID: ' . $event->id . ') on ' . $event->evnt_date_start . '.');
        } else {
            Log::info('Event created by sync: ' . '"' . $event->evnt_title . '" (eventID: ' . $event->id . ') on ' . $event->evnt_date_start . '.');
        }
        CacheUtility::forgetMonthTable($event);
    }

    public function updating(ClubEvent $event) 
    {
        if ($event->isDirty('evnt_time_start')) {
            Logging::eventStartChanged($event);
        }

        if ($event->isDirty('evnt_title')) {
            Logging::eventTitleChanged($event);
        }

        if ($event->isDirty('evnt_subtitle')) {
            Logging::eventSubtitleChanged($event);
        }

        if ($event->isDirty('evnt_time_end')) {
            Logging::eventEndChanged($event);
        }

        if ($event->isDirty('evnt_public_info')) {
            Logging::logEventRevision($event, "revisions.eventPublicInfoChanged");
        }

        if ($event->isDirty('evnt_private_details')) {
            Logging::logEventRevision($event, "revisions.eventPrivateDetailsChanged");
        }
        CacheUtility::forgetMonthTable($event);
    }


    public function updated(ClubEvent $event) 
    {
        $user = Auth::user();
        
        if ($user) {
            Log::info('Event edited: ' . $user->name . ' (' . $user->person->prsn_ldap_id . ', '
                . ') edited event "' . $event->evnt_title . '" (eventID: ' . $event->id . ') on ' . $event->evnt_date_start . '.');
        } else {
            Log::info('Event edited by sync: "' . $event->evnt_title . '" (eventID: ' . $event->id . ') on ' . $event->evnt_date_start . '.');
        }
        CacheUtility::forgetMonthTable($event);
    }

    public function deleted(ClubEvent $event) 
    {
        $user = Auth::user();
        if ($user) {
            Log::info('Event deleted: ' . $user->name . ' (' . $user->person->prsn_ldap_id . ', '
                . ') deleted event "' . $event->evnt_title . '" (eventID: ' . $event->id . ') on ' . $event->evnt_date_start . '.');
        } else {
            Log::info('Event deleted by sync: "' . $event->evnt_title . '" (eventID: ' . $event->id . ') on ' . $event->evnt_date_start . '.');
        }
        CacheUtility::forgetMonthTable($event);
    }
}
