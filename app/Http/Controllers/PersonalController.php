<?php

namespace Lara\Http\Controllers;

use DateTime;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\View;
use Lara\Http\Requests;
use Lara\Person;
use Lara\ScheduleEntry;

class PersonalController extends Controller
{

    public function statistics() {

        $userShifts = ScheduleEntry::where('prsn_id', '=', 7667)->get();
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

    public function chartData() {

        $userShifts = ScheduleEntry::where('prsn_id', '=', 7667)->get();
        $shiftsByMonth = $userShifts->groupBy(function(ScheduleEntry $value) {
            $date = new DateTime($value->schedule->event->evnt_date_start);
            return $date->format('Y-m');
        })->map(function($value) {
            return count($value);
        });

        $json = ['personal' => $shiftsByMonth];
        return response()->json($json);
    }
}
