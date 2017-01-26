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
        $monthStart = new DateTime($year . '-' . $month . '-01');
        $monthEnd = new DateTime($monthStart->format('Y-m-d'));
        $monthEnd->modify('next month')->modify('-1 day');

        $clubInfos = $this->statisticsFromTill($monthStart, $monthEnd);

        $infos = $clubInfos->flatten();

        list($semesterStart, $semesterEnd) = $this->semesterData();

        $semesterInfos = $this->statisticsFromTill($semesterStart, $semesterEnd);
        
        return View::make('statisticsView', compact('infos', 'clubInfos', 'userId', 'year', 'month', 'semesterInfos'));

    }

    /**
     * @param $from
     * @param $till
     * @return mixed
     */
    public function statisticsFromTill($from, $till)
    {
        $shifts = ScheduleEntry::whereHas('schedule.event', function ($query) use ($from, $till) {
            $query->whereBetween('evnt_date_start', [$from->format('Y-m-d'), $till->format('Y-m-d')]);
        })->get();
        $clubs = Club::activeClubs()->with('activePersons')->get();

        // array with key: clb_title and values: array of infos for user of the club
        $clubInfos = $clubs->flatMap(function ($club) use ($shifts) {
            $infosForClub = $club->activePersons->map(function ($person) use ($shifts, $club) {
                $info = new StatisticsInformation();
                return $info->make($person, $shifts, $club);
            });
            $maxShifts = $infosForClub->map(function ($info) {
                return $info->inOwnClub + $info->inOtherClubs;
            })->sort()->last();

            // avoid division by zero
            $maxShifts = max($maxShifts, 1);
            $infosForClub = $infosForClub->sortBy('user.prsn_name')
                ->map(function (StatisticsInformation $info) use ($maxShifts) {
                    $info->shiftsPercentIntern = $info->inOwnClub / ($maxShifts * 1.5) * 100;
                    $info->shiftsPercentExtern = $info->inOtherClubs / ($maxShifts * 1.5) * 100;
                    return $info;
                });
            return [$club->clb_title => $infosForClub];
        });
        return $clubInfos;
    }

    /**
     * Get the start and end of the current semester
     * @return array
     */
    public function semesterData(): array
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');

        if ($currentMonth < 4) {
            $semesterStart = new DateTime($currentYear - 1 . '-10-01');
            $semesterEnd = new DateTime($currentYear . '-03-31');
        }
        else if ($currentMonth < 10) {
            $semesterStart = new DateTime($currentYear . '-04-01');
            $semesterEnd = new DateTime($currentYear . '-09-30');
        }
        else {
            $semesterStart = new DateTime($currentYear . '-10-01');
            $semesterEnd = new DateTime($currentYear + 1 . '-03-31');
        }

        return array($semesterStart, $semesterEnd);
    }
}
