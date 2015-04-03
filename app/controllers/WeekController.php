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

class WeekController extends BaseController {
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

        $weekStart = date('Y-m-d', strtotime($year."W".$week.'1'));  		//Create week start date
        $weekEnd = date('Y-m-d', strtotime($year."W".$week.'7'));       	//Create week end date
		$weekStamp=date(strtotime($year."W".$week)); 						//Create week timestamp with year and weeknumber information (easier 	calculation)

        $nextWeek = date("W",strtotime("next Week".$weekStart)); 			// create number of next week
 	    $nextWeek = ($week==52 ? $year+1 : $year)."/KW".$nextWeek; 			// If week = years last week : set next to show year +1 otherwise use actual year
	    $previousWeek = date("W",strtotime("previous Week".$weekStart)); 	// create number of previous week
		$previousWeek = ($week==01 ? $year-1 : $year)."/KW".$previousWeek; 	// Same as above, but for first week

		$date = array('year' 			=> $year, 
					  'week' 			=> $week,
					  'weekStart' 		=> $weekStart,
					  'weekEnd'			=> $weekEnd,
					  'nextWeek'		=> $nextWeek,
					  'previousWeek'	=> $previousWeek,
					  'weekStamp'		=> $weekStamp );
				  
           
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
								->orderBy('prsn_name')
								->get();
			});

		$clubs = Club::lists('clb_title', 'id');

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
