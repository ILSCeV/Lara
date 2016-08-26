<?php

namespace Lara;


class StatisticsInformation
{

    public $totalShifts = 0;
    public $userName = "";
    public $userId = "";
    public $userClub = "";

    public function make(Person $person, $begin, $end)
    {
        $events = ClubEvent::where('evnt_date_start', '>=', $begin)
            ->where('evnt_date_end', '<=', $end)
            ->get();
        $shifts = $events
            ->flatMap(function (ClubEvent $event) use($person) {
                return $event->shifts()->where('prsn_id', '=', $person->id)->get();
            });

        $this->totalShifts = $shifts->count();
        $this->userName = $person->prsn_name;
        $this->userId = $person->prsn_ldap_id;
        $this->userClub = $person->getClub()->get()->first()->clb_title;
    }
}