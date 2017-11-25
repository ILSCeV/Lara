<?php

namespace Lara\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Lara\Person;
use Lara\Shift;

class ShiftStatisticsController extends Controller
{
    public function statisticsForTimeFrame(Shift $shift, $timeFrame = "month")
    {
        $type = $shift->type;

        $shiftDate = $shift->schedule->event->evnt_date_start;
        $section = $shift->schedule->event->section;

        $relevantShifts = $type->getJob()->whereHas('person')->with('event')
            ->get()
            ->filter(function(Shift $s) use($timeFrame, $shiftDate){
                if (!$s->schedule->event) {
                    return false;
                }
                $date = $s->schedule->event->evnt_date_start;
                if ($timeFrame == "month") {
                    return Carbon::createFromFormat('Y-m-d', $date)->isSameMonth(Carbon::createFromFormat('Y-m-d', $shiftDate));
                }
                if ($timeFrame == "year") {
                    return Carbon::createFromFormat('Y-m-d', $date)->isSameYear(Carbon::createFromFormat('Y-m-d', $shiftDate));
                }
                return true;
            })
            ->filter(function(Shift $s) use ($section) {
                return $s->schedule->event->section == $section;
            });

        $countPerPerson = $relevantShifts->map(function(Shift $s) {
            return $s->person;
        })->groupBy(function(Person $person) {
            return $person->nameWithStatus();
        })->map(function($item) {
            return collect($item)->count();
        })->sortBy(function($item) {
            return $item;
        });

        return response()->json($countPerPerson, 200, [], JSON_PRETTY_PRINT);
    }
}
