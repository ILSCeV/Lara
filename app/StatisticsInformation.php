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
        $usersShifts = $shifts->filter(function ($shift) use($person) { return $shift->person_id == $person->id;});

        $totalWeight = $usersShifts->reduce(function($prev, $current) {
            return $current->statistical_weight + $prev;
        });

        $shiftsInOwnClub = $usersShifts->filter(function ($shift) use ($club) {
            $visibleClubs = $shift->schedule->event->showToSectionNames();
            return in_array($club->clb_title, $visibleClubs);
        });

        $this->inOwnClub = $shiftsInOwnClub->reduce(function ($prev, $current) {
            return $current->statistical_weight + $prev;
        }, 0);

        $this->inOtherClubs = $totalWeight - $this->inOwnClub;

        $this->user = $person;
        $this->userClub = $club;
        
        return $this;
    }
}