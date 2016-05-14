<?php

namespace Lara\Http\Controllers;

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

		$date=array(  'year' => $year, 
		              'month' => $month,
					  'daysOfMonth' => $daysOfMonth, 	// Change to generated datetime from time input
					  'startDay' =>$startDay,
					  'endDay'=>$endDay,
					  'startStamp'=>$startStamp,
					  'usedTime'=>$usedTime,
					  'monthName'=>date("F", $usedTime) );

		$events = ClubEvent::where('evnt_date_start','>=',$monthStart)
						   ->where('evnt_date_start','<=',$monthEnd)
						   ->orderBy('evnt_date_start')
						   ->orderBy('evnt_time_start')
						   ->get();

		$surveys = Survey::where('in_calendar','>=',$monthStart)
							->where('in_calendar','<=',$monthEnd)
							->orderBy('in_calendar')
							->get();

		$tasks = Schedule::where('schdl_show_in_week_view', '=', '1')
			     ->where('schdl_due_date', '>=', $monthStart) 				
			     ->where('schdl_due_date', '<=', $monthEnd)
			     ->get();

		return View::make('monthView', compact('events', 'tasks', 'date', 'surveys'));
	}

}

