<?php

namespace Lara;


class StatisticsInformation
{

    public $totalShifts = 0;
    public $user;
    public $userClub = null;
    public $isActive = false;
    public $shiftsPercent = 0;

    public function make(Person $person, $shifts)
    {
        // use a flatMap, because shifts returns an array, so with a normal map we would have an array of arrays
        $usersShifts = $shifts->where('prsn_id', $person->id);

        $this->totalShifts = $usersShifts->count();

        $this->user = $person;
        $this->userClub = $person->getClub()->get()->first();
        
        return $this;
    }
}