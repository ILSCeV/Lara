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

class MonthController extends BaseController {
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
		
		// Array for german translation of the month names
		$monthName = array( 1	=> Config::get('messages_de.month-name-jan'),  
			                2	=> Config::get('messages_de.month-name-feb'),
			                3	=> Config::get('messages_de.month-name-mar'),
			                4	=> Config::get('messages_de.month-name-apr'),
			                5	=> Config::get('messages_de.month-name-may'),
			                6	=> Config::get('messages_de.month-name-jun'),
			                7	=> Config::get('messages_de.month-name-jul'),
			                8	=> Config::get('messages_de.month-name-aug'),
			                9	=> Config::get('messages_de.month-name-sep'),
			                10	=> Config::get('messages_de.month-name-oct'),
			                11	=> Config::get('messages_de.month-name-nov'),
			                12	=> Config::get('messages_de.month-name-dec'));

		$date=array(  'year' => $year, 
		              'month' => $month,
					  'daysOfMonth' => $daysOfMonth, 	// Change to generated datetime from time input
					  'startDay' =>$startDay,
					  'endDay'=>$endDay,
					  'startStamp'=>$startStamp,
					  'usedTime'=>$usedTime,
					  'monthName'=>$monthName[date("n", $usedTime)]);

		$events = ClubEvent::where('evnt_date_start','>=',$monthStart)
						   ->where('evnt_date_start','<=',$monthEnd)
						   ->orderBy('evnt_date_start')
						   ->orderBy('evnt_time_start')
						   ->get();

		return View::make('monthView', compact('events', 'date'));
	}

}

