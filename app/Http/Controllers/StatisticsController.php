<?php

namespace Lara\Http\Controllers;

use DateTime;
use Lara\Club;
use Lara\Person;
use Lara\Shift;
use Lara\StatisticsInformation;
use Redirect;
use View;

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
        $from = new DateTime($year.'-'.$month.'-01');
        $till = new DateTime($from->format('Y-m-d'));
        $till->modify('next month')->modify('-1 day');
        $isMonthStatistic = 1;
        list($clubInfos, $infos) = $this->generateStatisticInformationForSections($from, $till, $isMonthStatistic);
        
        return View::make('statisticsView',
            compact('infos', 'clubInfos', 'userId', 'year', 'month', 'isMonthStatistic'));
        
    }
    
    public function showYearStatistics($year = null)
    {
        if (!isset($year)) {
            $year = strftime('%Y');
        }
        $from = new DateTime($year.'-'.'01-01');
        $till = new DateTime($from->format('Y-m-d'));
        $till->modify('next year')->modify('-1 day');
        $isMonthStatistic = 0;
        list($clubInfos, $infos) = $this->generateStatisticInformationForSections($from, $till, $isMonthStatistic);
        $month = $till->format("m");
        
        return View::make('statisticsView',
            compact('infos', 'clubInfos', 'userId', 'year', 'month', 'isMonthStatistic'));
    }
    
    /**
     * Returns list of all shifts a selected person did in a chosen month, with some associated metadata
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function shiftsByPerson($id = null)
    {
        // fill empty parameters - no date selected means show current month and year
        if (!isset($id)) {
            return Redirect::action('StatisticsController@showStatistics');
        }
        request("year") ? $year = request("year") : $year = strftime('%Y');
        request("month") ? $month = request("month") : $month = strftime('%m');
        $isMonthStatistic = request("isMonthStatistic") == 0 ? 0 : 1;
        
        // set the time window
        if ($isMonthStatistic) {
            $from = new DateTime($year.'-'.$month.'-01');
            $till = new DateTime($from->format('Y-m-d'));
            $till->modify('next month')->modify('-1 day');
        } else {
            $from = new DateTime($year.'-'.'01-01');
            $till = new DateTime($from->format('Y-m-d'));
            $till->modify('next year')->modify('-1 day');
        }
        // get all shifts in selected time window, for selected person, with their attributes
        $shifts = Shift::where('person_id', '=', $id)
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
            
            $response[] = [
                'id'        => $shift->id,
                'shift'     => $shift->type->title(),
                'event'     => $shift->schedule->event->evnt_title,
                'event_id'  => $shift->schedule->event->id,
                'section'   => $shift->schedule->event->section->title,
                'isOwnClub' => in_array($ownClub, $clubsOfShift),
                'date'      => strftime("%d.%m.%Y (%a)", strtotime($shift->schedule->event->evnt_date_start)),
                'weight'    => $shift->statistical_weight,
            ];
        }
        
        return response()->json($response);
    }
    
    /**
     * @param bool $isMonthStatistic
     * @param DateTime $from
     * @param DateTime $till
     * @return array
     */
    private function generateStatisticInformationForSections($from, $till, $isMonthStatistic)
    {
        $year = $from->format("Y");
        $month = $till->format("m");
        
        $clubs = Club::activeClubs()->with('accountableForStatistics')->get();
        $persons = $clubs->flatMap(function ($club) {
            return $club->accountableForStatistics;
        });
        $persons = $persons->filter(function ($person) use ($from, $till) {
            return null !== $person->lastShift() && $person->shifts()->whereHas('schedule.event',
                    function ($query) use ($from, $till) {
                        $query->whereBetween('evnt_date_start', [$from->format('Y-m-d'), $till->format('Y-m-d')]);
                    })->get()->count() > 0;
        });
        
        $personIds = $persons->map(function ($prsn) {
            return $prsn->id;
        });
        $shifts = Shift::whereHas('schedule.event', function ($query) use ($from, $till) {
            $query->whereBetween('evnt_date_start', [$from->format('Y-m-d'), $till->format('Y-m-d')]);
        })->whereIn('person_id', $personIds)
            ->with('schedule')->with('schedule.event')
            ->get();
        
        
        // array with key: clb_title and values: array of infos for user of the club
        $clubInfos = $clubs->flatMap(function ($club) use ($shifts, $year, $month, $isMonthStatistic, $persons) {
            
            
            $infosForClub = $persons->filter(function ($person) use ($club) {
                return $person->clb_id == $club->id;
            })->map(function ($person) use ($shifts, $club) {
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
