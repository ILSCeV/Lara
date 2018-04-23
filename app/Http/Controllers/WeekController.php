<?php

namespace Lara\Http\Controllers;

use Lara\Survey;
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
use Lara\Section;

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
    	&& date("W") == "53") {
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
                           ->with('section',
                           		  'showToSection',
                           		  'getSchedule.shifts.type',
                           		  'getSchedule.shifts.getPerson.getClub')
                           ->orderBy('evnt_date_start')
                           ->orderBy('evnt_time_start')
                           ->get();

		$surveys = Survey::where('deadline', '>=', $weekStart)
							->where('deadline', '<=', $weekEnd)
							->orderBy('deadline')
							->get();
		
		$sections = Section::where('id', '>', 0)
                         ->orderBy('title')
                         ->get(['id', 'title', 'color']);

        // Filter - Workaround for older events: populate filter with event club
        /* @var $clubEvent ClubEvent */
        foreach ($events as $clubEvent) {
	        if ($clubEvent->showToSection->isEmpty()) {
	            $clubEvent->showToSection()->sync([$clubEvent->section->id]);
	            $clubEvent->save();
	        } 
        }

		$clubs = Club::orderBy('clb_title')->pluck('clb_title', 'id');
        
        return View::make('weekView', compact('events', 'schedules',  'date',
        									  'weekStart', 'weekEnd', 
											  'clubs', 'surveys', 'sections'));
	}
}
