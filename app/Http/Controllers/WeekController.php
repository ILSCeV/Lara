<?php

/* 
--------------------------------------------------------------------------
    Copyright (C) 2015  Maxim Drachinskiy
                        Silvi Kaltwasser
                        Nadine Sobisch
                        Robert Utnehmer

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details (app/LICENSE.txt).

    Any questions? Mailto: maxim.drachinskiy@bc-studentenclub.de
--------------------------------------------------------------------------
*/

class WeekController extends Controller {
	/**
	* Generate the view of the calender for given month and given year
	* with events in this period.
	*
	* @return RedirectResponse
	*
	*
	*/
	public function currentWeek()
    {
        return Redirect::action('WeekController@showWeek', array('year' => date("Y"), 
                                                                 'week' => date('W')));                                                               
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

		// TODO: don't use raw query, rewrite with eloquent.
		$persons = Cache::remember('personsForDropDown', 10 , function()
			{
				$timeSpan = new DateTime("now");
				$timeSpan = $timeSpan->sub(DateInterval::createFromDateString('3 months'));
				return Person::whereRaw("prsn_ldap_id IS NOT NULL 
										 AND (prsn_status IN ('aktiv', 'kandidat') 
										 OR updated_at>='".$timeSpan->format('Y-m-d H:i:s')."')")
								->orderBy('clb_id')
								->orderBy('prsn_name')
								->get();
			});

		$clubs = Club::orderBy('clb_title')->lists('clb_title', 'id');

		// IDs of schedules shown, needed for bulk-update
		$updateIds = array();

		foreach ($events as $event) {
			array_push($updateIds, $event->getSchedule->id);
		}

        return View::make('weekView', compact('events', 'schedules',  'date', 
        									  'tasks', 'entries', 'weekStart', 
        									  'weekEnd', 'persons', 'clubs'));

	}
		

}
