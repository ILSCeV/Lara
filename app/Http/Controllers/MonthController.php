<?php

namespace Lara\Http\Controllers;

use DateTime;
use DatePeriod;
use DateInterval;
use Lara\Http\Requests;
use Lara\Http\Controllers\Controller;

use Lara\Survey;
use Redirect;
use View; 

use Lara\ClubEvent;
use Lara\Schedule;


class MonthController extends Controller {

    /** 
     * Fills missing parameters: if no month specified use current year and month.
     *  
     * @return int $month
     * @return int $year
	 * @return RedirectResponse
     */        
    public function currentMonth()
    {
        return Redirect::action( 'MonthController@showMonth', ['year' => date("Y"), 
        													   'month' => date('m')] );                                                               
    }
     

	/**
	* Generate the view of the calender for given month and given year
	* with events in this period.
	*
	* @param string $year
	* @param string $month 
	* @return view monthView
	*/
	public function showMonth($year, $month) {
		// Create a string of the start of the month
        $monthStart = $year.$month.'01';

        $firstDay = new DateTime($monthStart);
        if ($firstDay->format('w') !== '1') {
            $firstDay->modify('previous Monday');
        }

        $lastDay = new DateTime($year . $month . $firstDay->format('t'));
        if ($lastDay->format('w' !== 0)) {
            $lastDay->modify('next Sunday');
        }

        // String of end of month
		$monthEnd = $year.$month.'31';  
	    
	    // Create timestamp of the first day of selected month. Time 12:12:12 used only as dummy time
	    $usedTime=mktime(12,12,12,$month,1,$year);  
	    
	    // Create a int with the number of days of the month (28...31)
	    $daysOfMonth=date("t",$usedTime);  

		// Create a timestamp with start of month
		$startStamp = mktime(0,0,0,date("n",$usedTime),1,date("Y",$usedTime)); 
		
		// Int for the start day
		$startDay = date("N", $startStamp);
		
		// Int for the lst day
		$endDay = date("N", mktime(0,0,0,date("n",$usedTime),$daysOfMonth,date("Y",$usedTime)));

        $date = array(
            'year' => $year,
            'month' => $month,
            'daysOfMonth' => $daysOfMonth,    // Change to generated datetime from time input
            'startDay' => $startDay,
            'endDay' => $endDay,
            'startStamp' => $startStamp,
            'usedTime' => $usedTime,
            'monthName' => date("F", $usedTime),
            'firstDay' => $firstDay,
            'lastDay' => $lastDay
        );

		$events = ClubEvent::where('evnt_date_start','>=', $firstDay->format("Y-m-d"))
						   ->where('evnt_date_start','<=', $lastDay->format("Y-m-d"))
						   ->orderBy('evnt_date_start')
						   ->orderBy('evnt_time_start')
						   ->get();

		$surveys = Survey::where('deadline','>=', $firstDay->format("Y-m-d"))
							->where('deadline','<=', $lastDay->format("Y-m-d"))
							->orderBy('deadline')
							->get();

        $mondays = new DatePeriod($firstDay, new DateInterval('P1W'), $lastDay->modify('+1 day'));
		return View::make('monthView', compact('events', 'date', 'surveys', 'firstDay', 'lastDay', 'mondays'));
	}
}

