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

use Illuminate\Database\Eloquent\Collection as Collection;

class EventController extends Controller {

    /**
     * Fills missing parameters: if no day specified use current date.
     *
     * @return int $day
     * @return int $month
     * @return int $year
	 * @return RedirectResponse
     */
	public function showCreateEventToday()
	{
		return Redirect::action('EventController@showCreateEvent', array('year' => date("Y"), 
                                                                     	 'month' => date("m"), 
                                                                     	 'day' => date("d")));
	}

    /**
	 * Generate the view for creating a new event with a date specified
	 *
	 * @param  int $year
     * @param  int $month
     * @param  int $day
	 *
	 * @return view createClubEventView
	 * @return Place[] places
	 * @return Schedule[] templates
	 * @return Jobtype[] jobtypes
	 * @return string $date
	 */
	public function showCreateEvent($year, $month, $day)
	{		
        $date = strftime("%d-%m-%Y", strtotime($year.$month.$day));

		$places = Place::orderBy('plc_title', 'ASC')
					   ->lists('plc_title', 'id');
		
		$templates = Schedule::where('schdl_is_template', '=', '1')
							 ->orderBy('schdl_title', 'ASC')
							 ->get();


		$jobtypes = Jobtype::where('jbtyp_is_archived', '=', '0')
						   ->orderBy('jbtyp_title', 'ASC')
						   ->get();
				
		return View::make('createClubEventView', compact('places', 
														 'templates', 
														 'jobtypes', 
														 'date'));
	}


    /**
     * Fills missing parameters: if no day specified use current date.
     *
     * @param  int $templateId
     *
     * @return int $day
     * @return int $month
     * @return int $year
	 * @return RedirectResponse
     */
	public function showCreateEventTodayWithTemplateToday($templateId)
	{
		return Redirect::action('EventController@showCreateEventWithTemplate', array('year' => date("Y"), 
                                                                     	 			 'month' => date("m"), 
                                                                     	 			 'day' => date("d"),
                                                                     	 			 'templateId'));
	}
	
    /**
	 * Generate the view for creating a new event with a template and a date specified.
	 *
	 * @param  int $year
     * @param  int $month
     * @param  int $day
     * @param  int $templateId
	 *
	 * @return view createClubEventView
	 * @return Place[] places
	 * @return Schedule[] templates
	 * @return Jobtype[] jobtypes
	 * @return string $date
	 */
	public function showCreateEventWithTemplate($year, $month, $day, $templateId)
	{		
		$date = strftime("%d-%m-%Y", strtotime($year.$month.$day));

		$places = Place::orderBy('plc_title', 'ASC')
					   ->lists('plc_title', 'id');
		
		$templates = Schedule::where('schdl_is_template', '=', '1')
							 ->orderBy('schdl_title', 'ASC')
							 ->get();

		$template = Schedule::where('id', '=', $templateId)
							->first();
		
		// put template data into entries
		$entries = $template->getEntries()
							->with('getJobType')
							->getResults();

		// put name of the active template for further use
		$activeTemplate = $template->schdl_title;

		$jobtypes = Jobtype::where('jbtyp_is_archived', '=', '0')
						   ->orderBy('jbtyp_title', 'ASC')
						   ->get();
				
		return View::make('createClubEventView', compact('places', 
														 'templates', 
														 'jobtypes', 
														 'entries', 
														 'activeTemplate', 
														 'date'));
	}

	/**
	* Generate the view for editing an event specified by $id.
	*
	* @param int $id
	* @return view editClubEventView
	*/
	public function showEditEvent($id)
	{ 
		$event = ClubEvent::findOrFail($id);

		$schedule = $event->getSchedule()
						  ->getResults();
		
		$jobtypes = Jobtype::where('jbtyp_is_archived', '=', '0')
						   ->orderBy('jbtyp_title', 'ASC')
						   ->get();

		$places = Place::orderBy('plc_title', 'ASC')
					   ->lists('plc_title', 'id');

		$templates = Schedule::where('schdl_is_template', '=', '1')
							 ->orderBy('schdl_title', 'ASC')
							 ->get();

		$template = Schedule::where('id', '=', $schedule->id)
							->first();
		
		// put template data into entries
		$entries = $schedule->getEntries()
							->with('getJobType')
							->getResults();

		// put name of the active template for further use
		$activeTemplate = $template->schdl_title;

		return View::make('editClubEventView', compact('event', 
													   'schedule', 
													   'places', 
													   'jobtypes', 
													   'templates', 
													   'template', 
													   'entries', 
													   'activeTemplate'));
	}
	

	/**
	* Create a new event with schedule and write it to the database.
	*
	* @return RedirectResponse
	*/
	public function createEvent()
	{
		//validate passwords
		if (Input::get('password') != Input::get('passwordDouble')) {
			Session::put('message', Config::get('messages_de.password-mismatch') );
            Session::put('msgType', 'danger');
			return Redirect::back()->withInput(); 
			}

		$newEvent = $this->editClubEvent(null);
		$newEvent->save();	

		$newSchedule = $this->editSchedule(null);
		$newSchedule->evnt_id = $newEvent->id;

		// log revision
		$newSchedule->entry_revisions = json_encode(array("0"=>
							   ["entry id" => null,
								"job type" => null,
								"action" => "Dienstplan erstellt",
								"old id" => null,
								"old value" => null,
								"new id" => null,
								"new value" => null,
								"user id" => Session::get('userId') != NULL ? Session::get('userId') : "",
								"user name" => Session::get('userId') != NULL ? Session::get('userName') . '(' . Session::get('userClub') . ')' : "Gast",
								"from ip" => Request::getClientIp(),
								"timestamp" => (new DateTime)->format('Y-m-d H:i:s')
							    ]));

		$newSchedule->save();

		$newEntries = ScheduleController::createScheduleEntries($newSchedule->id);
		foreach($newEntries as $newEntry)
		{
			$newEntry->schdl_id = $newSchedule->id;
			$newEntry->save();

			// log revision
			ScheduleController::logRevision($newEntry->getSchedule, 	// schedule object
											$newEntry,					// entry object
											"Dienst erstellt",			// action description
											null,						// old value
											null);						// new value
		}

		// log the action
		Log::info('Create event: User ' . Session::get('userName') . '(' . Session::get('userId') . ', ' 
				 . Session::get('userGroup') . ') created event ' . $newEvent->evnt_title . ' (ID: ' . $newEvent->id . ').');
			
		// show new event
		return Redirect::action('CalendarController@showId', array('id' => $newEvent->id));
	}
	
	/**
	* Edit an event specified by $id with schedule and write it to the database.
	*
	* @param int $id
	* @return RedirectResponse
	*/
	public function editEvent($id)
	{
		//validate passwords
		if (Input::get('password') != Input::get('passwordDouble')) {
			Session::put('message', Config::get('messages_de.password-mismatch') );
            Session::put('msgType', 'danger');
			return Redirect::back()->withInput(); 
			}
			
		// first we fill objects with data
		// if there is an error, we have not saved yet, so we need no rollback
		$event = $this->editClubEvent($id);

		$schedule = $this->editSchedule($event->getSchedule()->GetResults()->id);

		$entries = ScheduleController::editScheduleEntries($schedule->id);

		// log the action
		Log::info('Edit event: User ' . Session::get('userName') . '(' . Session::get('userId') . ', ' 
				 . Session::get('userGroup') . ') edited ' . $event->evnt_title . ' (ID:' . $event->id . ').');
		
		// save all data in the database
		$event->save();	
		$schedule->save();
		foreach($entries as $entry)
			$entry->save();
																		   
		// show event
		return Redirect::action('CalendarController@showId', array('id' => $id));
	}

	/**
	* Delete an event specified by parameter $id with schedule and scheduleEntries.
	* You can only delete, if you have rigths for marketing or clubleitung.
	*
	* @param int $id
	* @return RedirectResponse
	*/
	public function deleteEvent($id)
	{
		if(!Session::has('userId') 
			OR (Session::get('userGroup') != 'marketing'
				AND Session::get('userGroup') != 'clubleitung'))
		{
			Session::put('message', Config::get('messages_de.access-denied'));
            Session::put('msgType', 'danger');
			return Redirect::action('MonthController@showMonth', array('year' => date('Y'), 
                                                                   'month' => date('m')));
		}
		
		// at first get all data
		$event = ClubEvent::find($id);
		
		if (is_null($event)) {
			Session::put('message', Config::get('messages_de.event-doesnt-exist') );
            Session::put('msgType', 'danger');
			return Redirect::back();
		}
		
		// log the action
		Log::info('Delete event: User ' . Session::get('userName') . '(' . Session::get('userId') . ', ' 
				 . Session::get('userGroup') . ') deleted event ' . $event->evnt_title . ' (ID:' . $event->id . ').');

		$schedule = $event->getSchedule();
		
		$entries = $schedule->GetResults()->getEntries()->GetResults();
		
		// at least delete date in reverse order 'cause of dependencies in database
		foreach ($entries as $entry)
			$entry->delete();
		$schedule->delete();
		$event->delete();
		
		// show current month
		Session::put('message', Config::get('messages_de.event-delete-ok'));
        Session::put('msgType', 'success');
		return Redirect::action('MonthController@showMonth', array('year' => date('Y'), 
                                                                   'month' => date('m')));
	}
	
	// ---------------private functions ---------------------------------------	
	/**
	* Edit or create a clubevent with its entered information.
	* If $id is null create a new clubEvent, otherwise the clubEvent specified by $id will be edit. 
	*
	* @param int $id
	* @return ClubEvent clubEvent
	*/
	private function editClubEvent($id)
	{
		$event = new ClubEvent;
		if(!is_null($id)) {
			$event = ClubEvent::findOrFail($id);		
		}
		
		// format: strings; no validation needed
		$event->evnt_title 			 = Input::get('title');
		$event->evnt_subtitle 		 = Input::get('subtitle');
		$event->evnt_public_info 	 = Input::get('publicInfo');
		$event->evnt_private_details = Input::get('privateDetails');	
		$event->evnt_type 			 = Input::get('evnt_type');

		// create new place
		if (!Place::where('plc_title', '=', Input::get('place'))->first())		
		{
			$place = new Place;
			$place->plc_title = Input::get('place');
			$place->save();

			$event->plc_id = $place->id;
		}
		// use existing place
		else 	
		{
			$event->plc_id = Place::where('plc_title', '=', Input::get('place'))->first()->id;
		}

		// format: date; validate on filled value  
		if(!empty(Input::get('beginDate')))
		{
			$newBeginDate = new DateTime(Input::get('beginDate'), new DateTimeZone(Config::get('app.timezone')));
			$event->evnt_date_start = $newBeginDate->format('Y-m-d');
		}
		else
		{
			$event->evnt_date_start = date('Y-m-d', mktime(0, 0, 0, 0, 0, 0));;
		}
			
		if(!empty(Input::get('endDate')))
		{
			$newEndDate = new DateTime(Input::get('endDate'), new DateTimeZone(Config::get('app.timezone')));
			$event->evnt_date_end = $newEndDate->format('Y-m-d');
		}
		else
		{
			$event->evnt_date_end = date('Y-m-d', mktime(0, 0, 0, 0, 0, 0));;
		}
		
		// format: time; validate on filled value  
		if(!empty(Input::get('beginTime'))) $event->evnt_time_start = Input::get('beginTime');
		else $event->evnt_time_start = mktime(0, 0, 0);
		if(!empty(Input::get('endTime'))) $event->evnt_time_end = Input::get('endTime');
		else $event->evnt_time_end = mktime(0, 0, 0);
		
		// format: tinyInt; validate on filled value
		// reversed this: input=1 means "event is public", input=0 means "event is private"
		$event->evnt_is_private = (Input::get('isPrivate') == '1') ? 0 : 1;
		
		return $event;
	}

	/**
	* Edit or create a schedule with its entered information.
	* If $scheduleId is null create a new schedule, otherwise the schedule specified by $scheduleId will be edit. 
	*
	* @param int $scheduleId
	* @return Schedule newSchedule
	*/
	private function editSchedule($scheduleId)
	{
		$schedule = ScheduleController::editSchedule($scheduleId);
				
		$schedule->schdl_due_date = null;
		
		return $schedule;
	}
}



