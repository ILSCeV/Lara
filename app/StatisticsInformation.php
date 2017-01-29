<?php

namespace Lara;


class StatisticsInformation
{

    public $inOwnClub = 0;
    public $inOtherClubs = 0;
    public $user;
    public $userClub = null;
    public $isActive = false;
    public $shiftsPercentIntern = 0;
    public $shiftsPercentExtern = 0;


    public function make(Person $person, $shifts, Club $club)
    {
        $usersShifts = $shifts->where('prsn_id', $person->id);

        $totalWeight = $usersShifts->reduce(function($prev, $current) {
            return $current->entry_statistical_weight + $prev;
        });

        $shiftsInOwnClub = $usersShifts->filter(function ($shift) use ($club) {
            // reduce club id by one to cancel out the mismatch between Clubs and Places Table
            return $shift->schedule->event->plc_id === $club->id - 1;
        });

        $this->inOwnClub = $shiftsInOwnClub->reduce(function ($prev, $current) {
            return $current->entry_statistical_weight + $prev;
        }, 0);

        $this->inOtherClubs = $totalWeight - $this->inOwnClub;

        $this->user = $person;
        $this->userClub = $club;
        
        return $this;
    }
}