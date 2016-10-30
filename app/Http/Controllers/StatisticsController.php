<?php

namespace Lara\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;

use Lara\Club;
use Lara\ClubEvent;
use Lara\ScheduleEntry;
use View;
use Lara\Person;
use Session;

use Lara\Http\Requests;
use Lara\StatisticsInformation;

class StatisticsController extends Controller
{
    public function showStatistics($year = null, $month = null)
    {
        if (!isset($year)) {
            $year = strftime('%Y');
        }
        if (!isset($month)) {
            $month = strftime('%m');
        }
        $from = new DateTime($year . '-' . $month . '-01');
        $till = new DateTime($from->format('Y-m-d'));
        $till->modify('next month');

        $rewardableEventTypes = collect([
            0, // usual opening
            2, // special
            3, // live band / DJ
            6, // flooding
            7, // flyers
        ]);
        $shifts = ScheduleEntry::whereHas('schedule.event', function ($query) use ($from, $till, $rewardableEventTypes) {
            $query->whereBetween('evnt_date_start', [$from->format('Y-m-d'), $till->format('Y-m-d')])
                ->whereIn('evnt_type', $rewardableEventTypes);
        })->get();
        $clubs = Club::activeClubs()->with('activePersons')->get();

        // array with key: clb_title and values: array of infos for user of the club
        $clubInfos = $clubs->flatMap(function($club) use($shifts) {
            $infosForClub = $club->activePersons->map(function($person) use($shifts, $club)  {
                $info = new StatisticsInformation();
                return $info->make($person, $shifts, $club);
            });
            $maxShifts = $infosForClub->pluck('totalShifts')->sort()->last();

            // avoid division by zero
            $maxShifts = max($maxShifts, 1);
            $infosForClub = $infosForClub->sortBy('user.prsn_name')
                ->map(function(StatisticsInformation $info) use($maxShifts) {
                    $info->shiftsPercent = $info->totalShifts / ($maxShifts * 1.5) * 100;
                    return $info;
                });
            return [$club->clb_title => $infosForClub];
        });
        $userId = Session::get('userId');

        $infos = $clubInfos->flatten();
        
        $userInfo = $infos->where('user.prsn_ldap_id', $userId)->first();
        return View::make('statisticsView', compact('infos', 'userInfo', 'clubInfos', 'userId'));

    }
}
