<?php

namespace Lara\Http\Controllers;

use DateInterval;
use DatePeriod;
use DateTime;
use Lara\ClubEvent;
use Lara\Http\Middleware\RejectGuests;
use Lara\Section;
use Lara\Survey;
use Lara\utilities\CacheUtility;
use Redirect;
use View;


class MonthController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(RejectGuests::class, ['only' => ['markShiftsOfCurrentUser']]);
    }
    
    /**
     * Fills missing parameters: if no month specified use current year and month.
     *
     * @return int $month
     * @return int $year
     * @return RedirectResponse
     */
    public function currentMonth()
    {
        return Redirect::action('MonthController@showMonth', [
            'year'  => date("Y"),
            'month' => date('m'),
        ]);
    }
    
    
    /**
     * Generate the view of the calender for given month and given year
     * with events in this period.
     *
     * @param string $year
     * @param string $month
     * @return view monthView
     */
    public function showMonth($year, $month)
    {
        if(\Auth::user()) {
            return CacheUtility::remember('monthview-auth'.'-'.$year.'-'.$month,
                function () use ($year, $month) {
                    return $this->showMonthInternal($year, $month);
                }
            );
        } else {
            return CacheUtility::remember('monthview-guest'.'-'.$year.'-'.$month,
                function () use ($year, $month) {
                    return $this->showMonthInternal($year, $month);
                }
            );
        }
    }
    
    /**
     * Generate the view of the calender for given month and given year
     * with events in this period.
     *
     * @param string $year
     * @param string $month
     * @return view monthView
     */
    private function showMonthInternal($year, $month)
    {
        // create a DateTime object represeting the first monday in the timespan we want to display
        $firstDay = new DateTime($year.$month.'01');
        if ($firstDay->format('w') !== '1') {
            $firstDay->modify('previous Monday');
        }
        
        // create a DateTime object representing the last sunday in the timespan we want to display
        $lastDay = new DateTime($year.$month.$firstDay->format('t'));
        if ($lastDay->format('w' !== 0)) {
            $lastDay->modify('next Sunday');
        }
        
        // Create timestamp of the first day of selected month. Time 12:12:12 used only as dummy time
        $usedTime = mktime(12, 12, 12, $month, 1, $year);
        
        // Create a int with the number of days of the month (28...31)
        $daysOfMonth = date("t", $usedTime);
        
        // Create a timestamp with start of month
        $startStamp = mktime(0, 0, 0, date("n", $usedTime), 1, date("Y", $usedTime));
        
        // Int for the start day
        $startDay = date("N", $startStamp);
        
        // Int for the lst day
        $endDay = date("N", mktime(0, 0, 0, date("n", $usedTime), $daysOfMonth, date("Y", $usedTime)));
        
        $date = [
            'year'       => $year,
            'month'      => $month,
            'startDay'   => $startDay,
            'endDay'     => $endDay,
            'startStamp' => $startStamp,
            'usedTime'   => $usedTime,
            'monthName'  => date("F", $usedTime),
            'firstDay'   => $firstDay,
            'lastDay'    => $lastDay,
        ];
        
        $events = ClubEvent::where('evnt_date_start', '>=', $firstDay->format("Y-m-d"))
            ->where('evnt_date_start', '<=', $lastDay->format("Y-m-d"))
            ->with('showToSection')
            ->with('shifts')
            ->with('section')
            ->orderBy('evnt_date_start')
            ->orderBy('evnt_time_start')
            ->orderBy('plc_id')
            ->get();
        
        $surveys = Survey::where('deadline', '>=', $firstDay->format("Y-m-d"))
            ->where('deadline', '<=', $lastDay->format("Y-m-d"))
            ->orderBy('deadline')
            ->get();
        
        $mondays = new DatePeriod($firstDay, new DateInterval('P1W'), $lastDay->modify('+1 day'));
        
        $sections = Section::where('id', '>', 0)
            ->orderBy('title')
            ->get(['id', 'title', 'color']);
        
        return View::make('monthView',
            compact('events', 'date', 'surveys', 'firstDay', 'lastDay', 'mondays', 'sections'))->render();
    }
    
    public function markShiftsOfCurrentUser($year, $month)
    {
        return \Cache::rememberForever('monthshift-'.\Auth::user()->id.'-'.$year.'-'.$month,
            function () use ($year, $month) {
                // create a DateTime object represeting the first monday in the timespan we want to display
                $firstDay = new DateTime($year.$month.'01');
                if ($firstDay->format('w') !== '1') {
                    $firstDay->modify('previous Monday');
                }
                
                // create a DateTime object representing the last sunday in the timespan we want to display
                $lastDay = new DateTime($year.$month.$firstDay->format('t'));
                if ($lastDay->format('w' !== 0)) {
                    $lastDay->modify('next Sunday');
                }
                
                $clubEventIds = ClubEvent::query()
                    ->where('evnt_date_start', '>=', $firstDay)
                    ->where('evnt_date_start', '<=', $lastDay)
                    ->whereHas('shifts', function (\Illuminate\Database\Eloquent\Builder $query) {
                        $query->where('person_id', '=', \Auth::user()->person->id);
                    })->get(['id']);
                
                
                return response()->json($clubEventIds->toArray());
            });
        
    }
}
