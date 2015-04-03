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

class CalendarController extends BaseController {
        
    /** 
     * Fills missing parameters - if no year specified use current year.
     * 
     * @return int $year
	 * @return RedirectResponse
     */
    public function currentYear()
    {
        return Redirect::action('CalendarController@showYear', array('year' => date("Y")));                                                           
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
        return Redirect::action('MonthController@showMonth', array('year' => date("Y"), 
                                                                   'month' => date('m')));                                                               
    }


    /** 
     * Fills missing parameters: if no week number specified use current year and week.
     *
     * @return int $week
     * @return int $year
	 * @return RedirectResponse
     */       
    public function currentWeek()
    {
        return Redirect::action('WeekController@showWeek', array('year' => date("Y"), 
                                                                 'week' => date('W')));                                                               
    }


    /**
     * Fills missing parameters: if no day specified use current date.
     *
     * @return  int $day
     * @return  int $month
     * @return  int $year
	 * @return RedirectResponse
     */       
    public function currentDay()
    {
        return Redirect::action('CalendarController@showDate', array('year' => date("Y"), 
                                                                     'month' => date("m"), 
                                                                     'day' => date("d")));
    }
    

    /**
     * Generates the view for the list of all events in a year.
     *
     * @param  int $year
     *
     * @return view calendarView
     * @return ClubEvent[] $events
     * @return string $date
     */      
    public function showYear($year)
    {
        $yearStart = $year.'01'.'01';
        $yearEnd = $year.'12'.'31';

        $date = date("Y", strtotime($yearStart));

        $events = ClubEvent::where('evnt_date_start','>=',$yearStart)
                           ->where('evnt_date_start','<=',$yearEnd)
                           ->with('getPlace')
                           ->orderBy('evnt_date_start')
                           ->orderBy('evnt_time_start')
                           ->paginate(15);

        return View::make('calendarView', compact('events','date'));
    }
     

    /* For showMonth see MonthController@showMonth */


    /* For showWeek see WeekController@showWeek */


    /**
     * Generates the view for the list of all events on a specific date.
     *
     * @param  int $year
     * @param  int $month
     * @param  int $day
     *
     * @return view calendarView
     * @return ClubEvent[] $events
     * @return string $date
     */      
    public function showDate($year, $month, $day)
    {
        $dateInput = $year.$month.$day;

        $date = strftime("%a, %d. %b %Y", strtotime($dateInput));

        $events = ClubEvent::where('evnt_date_start','=',$dateInput)
                           ->with('getPlace')
                           ->paginate(15);

        return View::make('calendarView', compact('events', 'date'));
    }

    /**
     * Generates the view for a specific event, including the schedule.
     *
     * @param  int $id
     * @return view ClubEventView
     * @return ClubEvent $clubEvent
     * @return ScheduleEntry[] $entries
	 * @return RedirectResponse
     */
    public function showId($id)
    {  
		$clubEvent = ClubEvent::with('getPlace')
                              ->findOrFail($id);
		
		if(!Session::has('userId') 
        AND $clubEvent->evnt_is_private==1)
			
		{
			Session::put('message', Config::get('messages_de.access-denied'));
            Session::put('msgType', 'danger');
			return Redirect::action('MonthController@showMonth', array('year' => date('Y'), 
                                                                   'month' => date('m')));
		}
	
        $schedule = Schedule::findOrFail($clubEvent->getSchedule->id);

        $entries = ScheduleEntry::where('schdl_id', '=', $schedule->id)
                                ->with('getJobType',
                                       'getPerson', 
                                       'getPerson.getClub')
                                ->get();

        $clubs = Club::lists('clb_title', 'id');
		
		$persons = Cache::remember('personsForDropDown', 10 , function()
		{
			$timeSpan = new DateTime("now");
			$timeSpan = $timeSpan->sub(DateInterval::createFromDateString('3 months'));
			return Person::whereRaw("prsn_ldap_id IS NOT NULL AND (prsn_status IN ('aktiv', 'kandidat') OR updated_at>='".$timeSpan->format('Y-m-d H:i:s')."')")
							->orderBy('prsn_name')
							->get();
		});

        return View::make('clubEventView', compact('clubEvent', 'entries', 'clubs', 'persons'));
    }


/* TESTING AJAX HERE */

    public function showAjax()
    {
        $persons = Cache::remember('personsForDropDown', 10 , function()
        {
            $timeSpan = new DateTime("now");
            $timeSpan = $timeSpan->sub(DateInterval::createFromDateString('3 months'));
            return Person::whereRaw("prsn_ldap_id IS NOT NULL AND (prsn_status IN ('aktiv', 'kandidat') OR updated_at>='".$timeSpan->format('Y-m-d H:i:s')."')")
                            ->orderBy('prsn_name')
                            ->get();
        });

        return View::make('ajax', compact('persons'));
    }


    /**
     * Check if username exists
     * @return Response
     */
    public function posted()
    {
        try {
            // find our entry - 105 is the first comment on /calendar/id/18
            $find = ScheduleEntry::where('id', '=', '105')->first();

            // change value to input value
            $find->entry_user_comment = Input::get('input-test');
            $find->save();

            // send a response
            return Response::json(array("match"));

        } catch (Exception $e) {
            return Response::json(array("exception"));
        }
    }

/* END OF TESTING */


}