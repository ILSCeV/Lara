<?php

namespace Lara\Http\Controllers;

use DateTime;

use Lara\Club;
use Lara\Person;
use Lara\Shift;
use Redirect;
use View;

use Carbon\Carbon;

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
        $till->modify('next month')->modify('-1 day');
        $isMonthStatistic = 1;
        list($clubInfos, $infos) = $this->generateStatisticInformationForSections($from, $till, $isMonthStatistic);
        
        return View::make('statisticsView', compact('infos', 'clubInfos', 'userId', 'year', 'month', 'isMonthStatistic'));

    }

    public function showYearStatistics($year = null)
    {
        if (!isset($year)) {
            $year = strftime('%Y');
        }
        $from = new DateTime($year . '-' . '01-01');
        $till = new DateTime($from->format('Y-m-d'));
        $till->modify('next year')->modify('-1 day');
        $isMonthStatistic = 0;
        list($clubInfos, $infos) = $this->generateStatisticInformationForSections($from, $till, $isMonthStatistic);
        $month = $till->format("m");
    
        return View::make('statisticsView', compact('infos', 'clubInfos', 'userId', 'year', 'month', 'isMonthStatistic'));
    }

    /**
     * Returns list of all shifts a selected person did in a chosen month, with some associated metadata
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function shiftsByPerson($id = null)
    {
        // fill empty parameters - no date selected means show current month and year
        if (!isset($id)) { return Redirect::action( 'StatisticsController@showStatistics'); }
        request("year") ? $year = request("year") : $year = strftime('%Y');
        request("month") ? $month = request("month") : $month = strftime('%m');
        request("isMonthStatistic") == "0" ? $isMonthStatistic = 0 : $isMonthStatistic = 1;

        // set the time window
        if($isMonthStatistic == 1) {
            $from = new DateTime($year.'-'.$month.'-01');
            $till = new DateTime($from->format('Y-m-d'));
            $till->modify('next month')->modify('-1 day');
        } else {
            $from = new DateTime($year.'-'.'01-01');
            $till = new DateTime($from->format('Y-m-d'));
            $till->modify('next year')->modify('-1 day');
        }
        // get all shifts in selected time window, for selected person, with their attributes
        $shifts =  Shift::where('person_id', '=', $id)
                                ->whereHas('schedule.event', function ($query) use ($from, $till) {
                                    $query->whereBetween('evnt_date_start', [$from->format('Y-m-d'), $till->format('Y-m-d')]);
                                })
                                ->with('type', 'schedule.event.section')
                                ->get()
                                ->sortBy('schedule.event.evnt_date_start');

        // TODO: sort shifts by date
        
        // format the response
        $response = [];
        $ownClub = Person::find($id)->club->clb_title;


        foreach ($shifts as $shift) {
            $clubsOfShift = json_decode($shift->schedule->event->evnt_show_to_club);

            $response[] = [ 'id'        =>$shift->id, 
                            'shift'     =>$shift->type->title(),
                            'event'     =>$shift->schedule->event->evnt_title, 
                            'event_id'  =>$shift->schedule->event->id,
                            'section'   =>$shift->schedule->event->section->title,
                            'isOwnClub' =>in_array($ownClub, $clubsOfShift),
                            'date'      =>strftime("%d.%m.%Y (%a)", strtotime($shift->schedule->event->evnt_date_start)),
                            'weight'    =>$shift->statistical_weight];
        }
        return response()->json($response);        
    }
    
    /**
     * @param int $isMonthStatistic
     * @param DateTime $from
     * @param DateTime $till
     * @return array
     */
    private function generateStatisticInformationForSections($from, $till, $isMonthStatistic)
    {
        $shifts = Shift::whereHas('schedule.event', function ($query) use ($from, $till) {
            $query->whereBetween('evnt_date_start', [$from->format('Y-m-d'), $till->format('Y-m-d')]);
        })->get();
        $clubs = Club::activeClubs()->with('accountableForStatistics')->get();
        $year = $from->format("Y");
        $month = $till->format("m");
        
        // array with key: clb_title and values: array of infos for user of the club
        $clubInfos = $clubs->flatMap(function ($club) use ($shifts, $year, $month, $isMonthStatistic) {
            
            
            $infosForClub = $club->accountableForStatistics
                ->filter(function ($person) use ($year, $month, $isMonthStatistic) {
                    $lastShift = $person->lastShift();
                    if (is_null($lastShift)) {
                        return;
                    }
                    if($isMonthStatistic) {
                        // if members last shift was withing three months, display him. Otherwise don't
                        return $lastShift->updated_at->diffInMonths(Carbon::create($year, $month)) < 3;
                    } else {
                        // if members last shift was in the current year, display him. Otherwise don't
                        return $lastShift->updated_at->diffInYears(Carbon::create($year)) < 1;
                    }
                    
                })
                ->map(function ($person) use ($shifts, $club) {
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
        
        $infos = $clubInfos->flatten();
        
        return [$clubInfos, $infos];
    }
}
