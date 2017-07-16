<?php

namespace Lara\Http\Controllers;

use DateTime;

use Lara\Club;
use Lara\Person;
use Lara\Shift;
use Redirect;
use View;

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

        $shifts = Shift::whereHas('schedule.event', function ($query) use ($from, $till) {
            $query->whereBetween('evnt_date_start', [$from->format('Y-m-d'), $till->format('Y-m-d')]);
        })->get();
        $clubs = Club::activeClubs()->with('accountableForStatistics')->get();

        // array with key: clb_title and values: array of infos for user of the club
        $clubInfos = $clubs->flatMap(function($club) use($shifts) {
            $infosForClub = $club->accountableForStatistics->map(function($person) use($shifts, $club)  {
                $info = new StatisticsInformation();
                return $info->make($person, $shifts, $club);
            });
            $maxShifts = $infosForClub->map(function($info){return $info->inOwnClub + $info->inOtherClubs;})->sort()->last();

            // avoid division by zero
            $maxShifts = max($maxShifts, 1);
            $infosForClub = $infosForClub->sortBy('user.prsn_name')
                ->map(function(StatisticsInformation $info) use($maxShifts) {
                    $info->shiftsPercentIntern = $info->inOwnClub / ($maxShifts * 1.5) * 100;
                    $info->shiftsPercentExtern = $info->inOtherClubs / ($maxShifts * 1.5) * 100;
                    return $info;
                });
            return [$club->clb_title => $infosForClub];
        });

        $infos = $clubInfos->flatten();
        
        return View::make('statisticsView', compact('infos', 'clubInfos', 'userId', 'year', 'month'));

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

        // set the time window
        $from = new DateTime($year . '-' . $month . '-01');
        $till = new DateTime($from->format('Y-m-d'));
        $till->modify('next month')->modify('-1 day');

        // get all shifts in selected time window, for selected person, with their attributes
        $shifts =  Shift::where('person_id', '=', $id)
                                ->whereHas('schedule.event', function ($query) use ($from, $till) {
                                    $query->whereBetween('evnt_date_start', [$from->format('Y-m-d'), $till->format('Y-m-d')]);
                                })
                                ->with('type', 'schedule.event.place')
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
                            'section'   =>$shift->schedule->event->place->plc_title,
                            'isOwnClub' =>in_array($ownClub, $clubsOfShift),
                            'date'      =>strftime("%d.%m.%Y (%a)", strtotime($shift->schedule->event->evnt_date_start)),
                            'weight'    =>$shift->statistical_weight];
        }

        return response()->json($response);        
    }
}
