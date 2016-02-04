<?php

namespace Lara\Http\Controllers;

use Request;
use Redirect;
use View;
use Cache;
use DateTime;
use DateInterval;

use Lara\Http\Requests;
use Lara\Http\Controllers\Controller;

use Lara\ClubEvent;
use Lara\Schedule;
use Lara\Person;
use Lara\Club;

class WeekController extends Controller {

    /** 
     * Fills missing parameters: if no week number specified use current year and week.
     *
     * @return int $week
     * @return int $year
	 * @return RedirectResponse
     */       
    public function currentWeek()
    {
    	// A hack to correct wrong date in january starting in week 53 last year
    	if (date("m") == "01"
    	AND date("W") == "53") {
    		return Redirect::action('WeekController@showWeek', array('year' => date("Y",strtotime("-1 year")), 
                                                                 	 'week' => date('W'))); 
    	} else {
	        return Redirect::action('WeekController@showWeek', array('year' => date("Y"), 
    	                                                             'week' => date('W')));                                                               
    	}
    }
	/**
	* Generate the view of the week for given month and given year
	* with events in this period.
	*
	* @param string $year
	* @param string $week 
	* @return view weekView
	*
	*
	*/
	public function showWeek($year,$week)
    {
		// Create week start date on monday (day 1)
        $weekStart = date('Y-m-d', strtotime($year."W".$week.'1'));  

        // Create the number of the next week
		$nextWeek = date("W",strtotime("next Week".$weekStart));
		$nextYear = date("Y",strtotime("next Week".$weekStart)); 

        // Create week end date - we go till tuesday (day 2) because café needs alternative week view (Mi-Di)
        $weekEnd = date('Y-m-d', strtotime($nextYear."W".$nextWeek.'2'));
		
 	    // Create the number of the previous week
	    $previousWeek = date("W",strtotime("previous Week".$weekStart));
	    $previousYear = date("Y",strtotime("previous Week".$weekStart)); 

		// Convert number of prev/next week to verbatim format - needed for correct << and >> button links
 	    $nextWeek 	  = $nextYear."/KW".$nextWeek;
 		$previousWeek = $previousYear."/KW".$previousWeek; 


		$date = array('year' 			=> $year, 
					  'week' 			=> $week,
					  'weekStart' 		=> $weekStart,
					  'weekEnd'			=> $weekEnd,
					  'nextWeek'		=> $nextWeek,
					  'previousWeek'	=> $previousWeek );
				  
           
        $events = ClubEvent::where('evnt_date_start','>=',$weekStart)
                           ->where('evnt_date_start','<=',$weekEnd)
                           ->with('getPlace',
                           		  'getSchedule.getEntries.getJobType',
                           		  'getSchedule.getEntries.getPerson.getClub')
                           ->orderBy('evnt_date_start')
                           ->orderBy('evnt_time_start')
                           ->get();

		$tasks = Schedule::where('schdl_show_in_week_view', '=', '1')
					     ->where('schdl_due_date', '>=', $weekStart) 				
					     ->where('schdl_due_date', '<=', $weekEnd) 
					     ->with('getEntries.getPerson.getClub',
					     		'getEntries.getJobType')
					     ->get();


		$persons = Cache::remember('persons-club-2', 10 , function()
		{
			return  \Lara\Person::whereNotNull( "prsn_ldap_id" )
								// Get all active members from club 2
								->where( "clb_id", "=", "2" )
								->whereIn( "prsn_status", ['member', 'candidate'] )
								->orWhere(function($query)
								{
									// And add to the list any retired members only if they made a shift in the last 3 month
									$timeSpan = new DateTime("now");
									$timeSpan = $timeSpan->sub(DateInterval::createFromDateString('3 months'));

									$query->whereNotNull( "prsn_ldap_id" )
										  ->where( "clb_id", "=", "2" )
										  ->where( "updated_at", ">=", $timeSpan->format('Y-m-d H:i:s') );	
								})
							   	->orderBy('prsn_name')
							   	->get();
		});

		$clubs = Club::orderBy('clb_title')->lists('clb_title', 'id');

        return View::make('weekView', compact('events', 'schedules',  'date', 
        									  'tasks', 'entries', 'weekStart', 
        									  'weekEnd', 'persons', 'clubs'));

	}
		

}
