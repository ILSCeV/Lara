<?php

/* 
--------------------------------------------------------------------------
    Copyright (C) 2015  Maxim Drachinskiy

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

/** 
 * WILL BE IMPLEMENTED LATER. CURRENT STATE IS JUST AN ARCHIVE OF POSSIBLE FUNCTIONS. 
 *
 */

use Illuminate\Database\Eloquent\Collection as Collection;

class StatisticsController extends BaseController {


	/**
	 * Generates the view with all existing persons and their calculated statistics.
	 *
	 * @return Person[] persons
	 */
	public function showStatistics()
	{	
		$persons = Cache::remember('personsForStats', 10 , function()
		{
			$timeSpan = new DateTime("now");
			$timeSpan = $timeSpan->sub(DateInterval::createFromDateString('3 months'));
			return Person::whereRaw("prsn_ldap_id IS NOT NULL AND (prsn_status IN ('aktiv', 'kandidat') OR updated_at>='"	.$timeSpan->format('Y-m-d H:i:s')."')")
							->orderBy('prsn_name')
							->get();
		});

		$from = date("Y") . "-" . date("m") . "-01"; 
		$till = '2015-01-31';

		foreach($persons as $person){
			//define new attribute 'total' for each person object, fill it with the sum received.
			$person->total = $this->countStatisticsById($person->id, $from, $till);
		}

		return View::make('statisticsView', compact('persons'));
	}

	/**
	 * Calculates the sum of all shifts in a given period for a given person id.
	 *
	 * @return int 		$subjectTotal
	 */
	public function countStatisticsById($id, $from, $till)
	{		
		// get all events for the month, currently jan15 for testing
		$events = ClubEvent::where('evnt_date_start','>', $from)
						  ->where('evnt_date_start','<', $till)
						  ->get();

		$subject = Person::find($id);

		$subjectTotal = 0;

		foreach($events as $event){
			// for each event - get its schedule
			$schedule = Schedule::findOrFail($event->getSchedule->id);

			// for each schedule - get its entries that have this person's id
			$entries = ScheduleEntry::where('schdl_id', '=',$schedule->id)->where('prsn_id','=',$subject->id)->get();

			// for each entry - get its job type weight
			foreach($entries as $entry){
				$weight = $entry->getJobType->jbtyp_statistical_weight;
				//and add it to subject's total
				$subjectTotal += $weight;
			}
			
		}

		return $subjectTotal;
	}

}



