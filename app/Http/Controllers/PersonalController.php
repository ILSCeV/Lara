<?php

namespace Lara\Http\Controllers;

use \DateInterval;
use \DatePeriod;
use \DateTime;

use Illuminate\Support\Facades\View;
use Lara\Person;
use Lara\ScheduleEntry;

class PersonalController extends Controller
{

    public function statistics($prsn_id = 7667) {

        $userShifts = ScheduleEntry::where('prsn_id', '=', $prsn_id)->get();
        $shiftsByTitle = $userShifts->groupBy(function(ScheduleEntry $value) {
            return $value->jobType->jbtyp_title;
        });
        $shiftTitleByFrequency = $shiftsByTitle->map(function ($value, $key) {
            return $key;
        })->sortBy(function ($key) use ($shiftsByTitle) {
            return count($shiftsByTitle[$key]);
        });
        $mostDoneShift = $shiftTitleByFrequency->last();
        $leastDoneShift = $shiftTitleByFrequency->first();

        return View::make('personalStatisticsView', compact('mostDoneShift', 'leastDoneShift', 'shiftTitleByFrequency'));
    }

    public function chartData($prsn_id = 7667) {
        $userShifts = ScheduleEntry::where('prsn_id', '=', $prsn_id)->get();
        $shiftsByMonth = $this->shiftsByMonth($userShifts);
        $json = ['personal' => $shiftsByMonth];
        return response()->json($json);
    }

    public function averageData($prsn_id = 7667) {

        $person = Person::find($prsn_id);
        $club = $person->club;
        $place = $club->correspondingPlace();

        $allShifts = ScheduleEntry::whereHas('schedule.event', function($query) use($place) {
            $query->where('plc_id', $place->id);
        })->get();

        $activeMembers = Person::whereIn('prsn_status', ['member', 'candidate'])
            ->where('clb_id', $club->id)
            ->count();
        $shiftsByMonth = $this->shiftsByMonth($allShifts)
            // average the shifts by dividing through active members
            ->map(function($value) use ($activeMembers) {
                return $value / $activeMembers;
            });

        $json = ['average' => $shiftsByMonth];
        return response()->json($json);
    }

    /**
     * @param $shifts
     * @return mixed
     */
    public function shiftsByMonth($shifts)
    {
        $byMonth = $shifts->groupBy(function (ScheduleEntry $value) {
            $date = new DateTime($value->schedule->event->evnt_date_start);
            return $date->format('Y-m');
        })->map(function ($entries) {
            // get total weight done
            // on personal page we just count all shifts, without splitting them between clubs
            return $entries->map(function(ScheduleEntry $entry) {
                return $entry->entry_statistical_weight;
            })->sum();
        });

        // if you did no shifts in a month it will not appear in the data.
        // to fix this, we will have to find the missing months and add them (with 0 shifts done)
        $firstMonth = $byMonth->keys()->first();
        // set to today, if you did no shifts in this month we still want to display the average
        $lastMonth = 'today';
        $monthInterval = new DateInterval('P1M');

        $period = new DatePeriod(new DateTime($firstMonth), $monthInterval, new DateTime($lastMonth));

        foreach ($period as $month) {
            $formatted = $month->format('Y-m');
            if(! $byMonth->keys()->contains($formatted)) {
                $byMonth[$formatted] = 0;
            }
        }
        return $byMonth;
    }
}
