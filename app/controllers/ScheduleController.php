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

class ScheduleController extends BaseController {


    /**
	* Generates the view for a list of schedules with a relation to an event.
	*
	* @return view scheduleViewList
	* @return Schedule[] $schedules 
	*/
	public function showScheduleList()
	{	
		if(Session::has('userId')) {
	
		$schedules = Schedule::join('club_events', 'schedules.evnt_id', '=', 'club_events.id')
							 ->orderBy('club_events.evnt_date_start', 'DESC')
							 ->paginate(20);

		return View::make('scheduleViewList', compact('schedules'));

		} else {
		
		$schedules = Schedule::join('club_events', 'schedules.evnt_id', '=', 'club_events.id')
							 ->where('evnt_is_private', '=' , '0') 
							 ->orderBy('club_events.evnt_date_start', 'DESC')
							 ->paginate(20);

		return View::make('scheduleViewList', compact('schedules'));
		}
	}

    /**
	 * Generates the view for list of tasks - schedules with no related event.
	 *
	 * @return view scheduleViewList
	 * @return Schedule[] $schedules 
	 */
	public function showTaskList()
	{	
		$schedules = Schedule::whereNull('evnt_id')
							   ->orderBy('schdl_due_date', 'DESC')
							   ->paginate(20);
						   
		return View::make('taskViewList', compact('schedules'));
	}
	

	/**
	 * Generates the view for a specific schedule.
	 *
	 * Also used for a specific task (schedule without a related event).
	 *
	 * @param int $id
	 * @return view scheduleViewById
	 * @return Schedule $schedule 
	 * @return ScheduleEntry[] $entries 
	 */
	public function showSchedule($id)
	{	
		$schedule = Schedule::findOrFail($id);
	
		if(!Session::has('userId') AND 
			(is_null($schedule->evnt_id) OR $schedule->getClubEvent()->GetResults()->evnt_is_private==1))
			
		{
			Session::put('message', Config::get('messages_de.access-denied')); 
			Session::put('msgType', 'danger');
			
			return Redirect::action('MonthController@showMonth', array('year' => date('Y'), 
                                                                   	   'month' => date('m') ) );
		}
	
		$entries = ScheduleEntry::where('schdl_id', '=', $id)->get();

		$clubs = Club::lists('clb_title', 'id');

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
		
		return View::make('scheduleViewById', compact('entries', 'schedule', 'clubs', 'persons'));
	}



	/**
	 * Updates schedule entries of a specific schedule.
	 *
	 * If a password is needed, check it's correct and throw an error to the session if it's not,
	 * update all entries in bulk otherwise.
	 *
	 * We also use this method for tasks (schedules without a related event).
	 *
	 * @param int $id
     *
	 * @return RedirectResponse
	 */
    public function updateSchedule($id)
    {
		$schedule = Schedule::findOrFail($id);
		$entries = ScheduleEntry::where('schdl_id','=',$id)->get();
    	
    	// Check if that schedule needs a password
		if ($schedule->schdl_password !== '')
		{
			//get password for specific id here, similar to enty->id
			if(!Hash::check(Input::get('password'), $schedule->schdl_password ))
			{
				Session::put('message', Config::get('messages_de.schedule-pw-needed')); 
				Session::put('msgType', 'danger');
				return Redirect::back();
			} 	
		} 

		foreach($entries as $entry)
		{
			// Entry was empty
			if( !isset($entry->prsn_id) )
			{
				// Entry is not empty now
				if ( !Input::get('userName' . $entry->id) == '' 
			 	  OR !Input::get('userName' . $entry->id) == Config::get('messages_de.no-entry'))
				{
					// Add new entry data
					$this->onAdd($entry);
				} 
				// Otherwise no change found - do nothing
			}
			// Entry was not empty
			else
			{
				// Same person there?
				if( $entry->prsn_id == Input::get('userName' . $entry->id) 
				AND Person::where('id', '=', $entry->prsn_id)->first()->prsn_ldap_id == Input::get('ldapId'. $entry->id) )
				{
					// Was comment updated?
					if ( $entry->entry_user_comment != Input::get('comment' . $entry->id) )
					{
						$entry->entry_user_comment = Input::get('comment' . $entry->id);
					} 
					// Otherwise no change found - do nothing
				}
				// New data entered
				else
				{
					// Was entry deleted?
					if ( Input::get('userName' . $entry->id) == '' 
				 	  OR Input::get('userName' . $entry->id) == Config::get('messages_de.no-entry'))
					{
						$this->onDelete($entry);
					}
					// So some new person was provided
					else
					{
						// delete old data
						$this->onDelete($entry);
						// add new data
						$this->onAdd($entry);
					}
				}
			}	
		}

		Session::put('message', Config::get('messages_de.schedule-update-ok')); 
		Session::put('msgType', 'success');

		return Redirect::back();	
	}


	/**
	 * Updates multiple schedules.
	 */
	public function bulkUpdateSchedule($year, $week)
	{
		$weekStart = date('Y-m-d', strtotime($year."W".$week.'1'));  		// Create week start date
        $weekEnd = date('Y-m-d', strtotime($year."W".$week.'7'));       	// Create week end date
		$updateIds = array();												// Create (empty) index of all schedules we need to update

		// Collect IDs of event schedules shown in chosen week view
		$events = ClubEvent::where('evnt_date_start','>=',$weekStart)
                           ->where('evnt_date_start','<=',$weekEnd)
                           ->orderBy('evnt_date_start')
                           ->orderBy('evnt_time_start')
                           ->get();
		
		// Add them to the index
		foreach ($events as $event) {
			array_push($updateIds, $event->getSchedule->id);
		}

		// Collect IDs of tasks shown in week view
		$tasks = Schedule::where('schdl_show_in_week_view', '=', '1')
					     ->where('schdl_due_date', '>=', $weekStart) 				
					     ->where('schdl_due_date', '<=', $weekEnd) 
					     ->get();

		// Add them to the index
		foreach ($tasks as $task) {
			array_push($updateIds, $task->id);
		}

		// update each of the elements in the index
		foreach ($updateIds as $scheduleId) {
			$this->updateSchedule($scheduleId);
		}

		return Redirect::back();
	}


	/**
	* Edit or create a schedule with its entered information.
	* If $scheduleId is null create a new schedule, otherwise the schedule specified by $scheduleId will be edit. 
	*
	* @param int $scheduleId
	* @return Schedule newSchedule
	*/
	public static function editSchedule($scheduleId)
	{
		$schedule = new Schedule;

		if (!is_null($scheduleId)){
			$schedule = Schedule::findOrFail($scheduleId);
		}
		
		// format: time; validate on filled value  
		if(!empty(Input::get('preparationTime'))) $schedule->schdl_time_preparation_start = Input::get('preparationTime');
		else $schedule->schdl_time_preparation_start = mktime(0, 0, 0);
		
		// format: date; validate on filled value  
		// by tasks - stay this way, by schedules will be set to null by event creation.
		// ToDo: evtl statt null auf today setzen? M.
		if(!empty(Input::get('dueDate')))
		{
			$dueDate = new DateTime(Input::get('dueDate'), 
					   new DateTimeZone(Config::get('app.timezone')));
			$schedule->schdl_due_date = $dueDate->format('Y-m-d');
		}
		else $schedule->schdl_due_date = date('Y-m-d', time());
		
		// format: password; validate on filled value  
		if (Input::get('password') == "delete"
			AND Input::get('passwordDouble') == "delete") {
			//delete password or leave empty
			$schedule->schdl_password = '';
		} elseif (!empty(Input::get('password'))
			AND !empty(Input::get('passwordDouble'))
			AND Input::get('password') == Input::get('passwordDouble')) {
				$schedule->schdl_password = Hash::make(Input::get('password'));
		} 
		
		// format: tinyInt; validate on filled value 
		if (Input::get('saveAsTemplate') == true)
		{
			$schedule->schdl_is_template = true;
			$schedule->schdl_title = Input::get('templateName');
			
		}
		else {
			$schedule->schdl_is_template = false;
		}
		
		return $schedule;
	}
	

	/**
	* Create all new scheduleEntries with its entered information.
	*
	* @return Collection scheduleEntries
	*/
	public static function createScheduleEntries()
	{
		$scheduleEntries = new Collection;

		// parsing jobtype entries
		for ($i=1; $i <= Input::get("counter"); $i++) { 

			if (!empty(Input::get("jobType" . $i))) { 		// skip empty fields
		        
			$jobtype = Jobtype::firstOrCreate(array('jbtyp_title'=>Input::get("jobType" . $i)));	
			$jobtype->jbtyp_time_start = Input::get('timeStart' . $i);
			$jobtype->jbtyp_time_end = Input::get('timeEnd' . $i);
			
			$scheduleEntry = new ScheduleEntry;
			$scheduleEntry->jbtyp_id = $jobtype->id;
			
			$scheduleEntries->add(ScheduleController::updateScheduleEntry($scheduleEntry, $jobtype->id, $i));
			}
		}
		
		return $scheduleEntries;
	}


	/**
	* Edit and/or delete scheduleEntries refered to $scheduleId.
	*
	* @param Schedule $schedule
	* @return Collection scheduleEntries
	*/
	public static function editScheduleEntries($scheduleId)
	{
		// get number of submitted entries
		$numberOfSubmittedEntries = Input::get('counter');

		// get old entries for this schedule
		$scheduleEntries = ScheduleEntry::where('schdl_id', '=', $scheduleId)->get();

		// prepare a collection for updated entries
		$newEntries = new Collection;
		
		// Counter to traverse all inputs from 1 to N
		$counterHelper = '1';

		// check for changes in each entry
		foreach ( $scheduleEntries as $entry ) {
			
			if ( $entry->getJobType == Input::get('jobType' + $counterHelper) ) {
				// same job type as before - do nothing
				// add to new collection
				$newEntries->add(ScheduleController::updateScheduleEntry($entry, $jobtype->id, $counterHelper));

			} elseif ( Input::get("jobType" . $counterHelper) == '' ) {
				// job type empty - delete entry
				$entry->delete();
			
			} else {
				// some new job type - change entry
				$jobtype = Jobtype::firstOrCreate(array('jbtyp_title'=>Input::get("jobType" . $counterHelper)));
				$entry->jbtyp_id = $jobtype->id;

				// add to new collection
				$newEntries->add(ScheduleController::updateScheduleEntry($entry, $jobtype->id, $counterHelper));
			}

			// move to next input
			$counterHelper++;
		}

		// changed all old entries - have any new ones been added?
		if ($numberOfSubmittedEntries > $counterHelper - 1) {
			// create some new fields
			
			for ($i= $counterHelper; $i <= $numberOfSubmittedEntries; $i++) { 
				
				// skip empty fields, create new fields if not input empty
				if (!empty(Input::get("jobType" . $i))) { 		
					$jobtype = Jobtype::firstOrCreate(array('jbtyp_title'=>Input::get("jobType" . $i)));
					
					$newEntry = new ScheduleEntry;
					$newEntry->jbtyp_id = $jobtype->id;
					$newEntry->schdl_id = $scheduleId;
					
					$newEntries->add(ScheduleController::updateScheduleEntry($newEntry, $jobtype->id, $i));
				}
			}
		}
		
		return $newEntries;
	}

	/**
	* Update start and end time of $newScheduleEntry with input of gui elements
	*
	* @param ScheduleEntry $scheduleEntry
	* @param int $jobtypeId
	* @param int $counterValue
	* @return ScheduleEntry updates scheduleentry
	*/
	private static function updateScheduleEntry($scheduleEntry, $jobtypeId, $counterValue)
	{
		if (!empty(Input::get('timeStart' . $jobtypeId))){
			$scheduleEntry->entry_time_start = Input::get('timeStart' . $counterValue);
		} else {
			$scheduleEntry->entry_time_start = "21:00";
		}
		
		if (!empty(Input::get('timeEnd' . $jobtypeId))){
			$scheduleEntry->entry_time_end = Input::get('timeEnd' . $counterValue);
		} else {
			$scheduleEntry->entry_time_end = "01:00";	
		}
			
		return $scheduleEntry;
	}

	
	// ---------------private functions ---------------------------------------	

	/**
	 * Deletes schedule entries.
	 *
	 * @param $entry
	 * @return void
	 */	
	private function onDelete($entry)
	{
		// Delete the dataset in table Person if it's a guest (LDAP id = NULL), but don't touch club members.
		if ( !isset($entry->getPerson->prsn_ldap_id ) )
		{
			Person::destroy($entry->prsn_id);
		}
		
		// Clear the entry
		$entry->prsn_id = null;
		$entry->entry_user_comment = null;

		$entry->save();
	}


/**
	 * Adds new person to the schedule entry.
	 *
	 * @param $entry
	 * @return void
	 */
	private function onAdd($entry)
	{

		if ( Input::get('ldapId' . $entry->id) == '' ) 
		{	
			// If no LDAP id provided - create new GUEST person
			$person = new Person;
			
			// LDAP ID 
			$person->prsn_ldap_id = null;

			// NAME
			$person->prsn_name = Input::get('userName' . $entry->id);
			
			// PERSON STATUS = empty for guests

		} 
		else 
		{	
			// Find existing MEMBER person in DB
			$person = Person::where('prsn_ldap_id', '=', Input::get('ldapId' . $entry->id) )->first();	

			// If not found - create new person with data provided
			if (is_null($person)) 
			{
				// LDAP ID - already in the DB for existing person, adding a new one for a new person
				$person = Person::create(array('prsn_ldap_id' => Input::get('ldapId' . $entry->id)));

				// NAME - already in the DB for existing person, adding a new one for a new person
				$person->prsn_name = Input::get('userName' . $entry->id);

				// PERSON STATUS 
				$person->prsn_status = Session::get('userStatus');
			}

			// If a person adds him/herself - update status from session to catch if it was changed in LDAP
			if ($person->prsn_ldap_id == Session::get('userId')) 
			{
				$person->prsn_status = Session::get('userStatus');
			}
			
		}
		
		// CLUB

		// If club input is empty setting clubId to '-' (clubId 1). 
		// Else - look for a match in the Clubs DB and set person->clubId = matched club's id.
		// No match found - creating a new club with title from input.
		if ( Input::get('club' . $entry->id) == ''
		  OR Input::get('club' . $entry->id) == '-' )
		{
			$person->clb_id = '1'; 
		} 
		else 
		{
			$match = Club::firstOrCreate(array('clb_title' => Input::get('club' . $entry->id)));	
			$person->clb_id = $match->id;
		}
			
		// COMMENT

		// Change current comment to new comment
		$entry->entry_user_comment = Input::get('comment' . $entry->id);
		
		
		// Save changes to person and schedule entry
		$person->updated_at = Carbon\Carbon::now();
		$person->save();

		$entry->prsn_id = $person->id;
	    $entry->save();
	}
	
}
