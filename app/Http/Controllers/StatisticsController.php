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

use Illuminate\Database\Eloquent\Collection as Collection;
use Khill\Lavacharts\Lavacharts;

class StatisticsController extends Controller {


	/**
	 * Generates the view with statistics for a chosen timeframe.
	 * Includes all persons with LDAP-id set AND status "aktiv" and "kandidat" 
	 * OR persons with LDAP-id and other status if they used a schedule in the last 3 month.
	 *
	 * @return int $from
	 * @return int $till
	 * @return RedirectResponse
	 */
	public function showStatistics(){

		// Setting timeframe
		$from = (is_null(Input::get('from'))) ? date('Y-m-01') : Input::get('from');
		$till = (is_null(Input::get('till'))) ? date('Y-m-t') : Input::get('till');	

		// Selecting persons to evaluate
		$timeSpan = new DateTime("now");
		$timeSpan = $timeSpan->sub(DateInterval::createFromDateString('3 months'));
		$persons = Person::whereRaw( "prsn_ldap_id IS NOT NULL 
									  AND (prsn_status IN ('aktiv', 'kandidat') 
									  OR updated_at>='" . $timeSpan->format('Y-m-d H:i:s') . "')")
						 ->orderBy('prsn_name')
						 ->get();
		

		foreach($persons as $person){
			// Define new attribute 'total' for each person object, fill it with the sum received
			$person->total = $this->countStatisticsById($person->id, $from, $till);
		}


		// Generating charts with lavacharts

		$graphData = Lava::DataTable();

		$graphData->addStringColumn('Name')
		          ->addNumberColumn('Dienste');

		foreach ($persons as $person) {
			$graphData->addRow(array($person->prsn_name . "(" . substr($person->prsn_status, 0, 1) . ")" . "(" . $person->getClub->clb_title . ")", $person->total));
		}


		$columnchart = Lava::ColumnChart('Dienste')
		                    ->setOptions(array('datatable' => $graphData));

		return View::make('statisticsView', compact('persons', 'from', 'till'));
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

