<?php

namespace Lara;


class StatisticsInformation
{

    public $totalShifts = 0;
    public $user;
    public $userClub = null;
    public $isActive = false;
    public $shiftsPercent = 0;

    public function make(Person $person, $shifts, Club $club)
    {
        $usersShifts = $shifts->where('prsn_id', $person->id)->filter(function($shift) use($club) {
            // reduce club id by one to cancel out the mismatch between Clubs and Places Table
            return $shift->schedule->event->plc_id === $club->id - 1;
        });

        $this->totalShifts = $usersShifts->reduce(function ($prev, $current) {
            return $current->entry_statistical_weight + $prev;
        }, 0);

        $this->user = $person;
        $this->userClub = $club;
        
        return $this;
    }
}