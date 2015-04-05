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

class TaskController extends Controller {

	/**
	* Generate the view for creating a new task.
	*
	* @return view createTaskView
	*/
	public function showCreateTask()
	{		
		$jobtypes = Jobtype::where('jbtyp_is_archived', '=', '0')
						   ->orderBy('jbtyp_title', 'ASC')
						   ->get();
		
		return View::make('createTaskView', compact('jobtypes'));
	}
	
	/**
	* Generate the view for editing a task specified by $id.
	*
	* @param int $id
	* @return view editTaskView
	*/
	public function showEditTask($id)
	{

		$schedule = Schedule::findOrFail($id);
		
		$entries = $schedule->getEntries()
							->with('getJobType')
							->GetResults();
		
		$jobtypes = Jobtype::where('jbtyp_is_archived', '=', '0')
						   ->orderBy('jbtyp_title', 'ASC')
						   ->get();

		return View::make('editTaskView', compact('schedule', 'jobtypes', 'entries'));
	}
	
	/**
	* Create a new task and write it to the database.
	*
	* @return RedirectResponse
	*/
	public function createTask()
	{
		// first we fill objects with data
		// if there is an error, we have not saved yet, so we need no rollback
		$newSchedule = $this->editSchedule(null);
		$newEntries = ScheduleController::createScheduleEntries();
		
		// capture showInWeekView-checkbox
		$newSchedule->schdl_show_in_week_view = !is_null(Input::get('showInWeekView'));

		// all data in the database
		$newSchedule->save();
		
		foreach($newEntries as $newEntry)
		{
			$newEntry->schdl_id = $newSchedule->id;
			$newEntry->save();
		}

		// log the action
		Log::info('Create task: User ' . Session::get('userId') . ' with rigths ' . Session::get('userGroup') . ' creates task ' . $newSchedule->schdl_title . ' with id ' . $newSchedule->id . '.');
			
		// show new task
		return Redirect::action('ScheduleController@showSchedule', array('id' => $newSchedule->id));
	}

	/**
	* Edit a task specified by $id and write it to the database.
	*
	* @param int $id
	* @return RedirectResponse
	*/
	public function editTask($id)
	{
		// first we fill objects with data
		// if there is an error, we have not saved yet, so we need no rollback
		$schedule = $this->editSchedule($id);

		$entries = ScheduleController::editScheduleEntries($id);

		// capture showInWeekView-checkbox
		$schedule->schdl_show_in_week_view = !is_null(Input::get('showInWeekView'));

		// log the action
		Log::info('Edit task: User ' . Session::get('userId') . ' with rigths ' . Session::get('userGroup') . ' edits task ' . $schedule->schdl_title . ' with id ' . $schedule->id . '.');
			
		// at least save all data in the database
		$schedule->save();
		foreach($entries as $entry)
			$entry->save();
		
		// show task
		return Redirect::action('ScheduleController@showSchedule', array('id' => $id));
	}

	/**
	* Delete a task specified by parameter $id with scheduleEntries.
	* You can only delete, if you have rigths for marketing or clubleitung.
	*
	* @param int $id
	* @return RedirectResponse
	*/
	public function deleteTask($id)
	{
		if(!Session::has('userId'))
		{
			Session::put('message', Config::get('messages_de.access-denied'));
            Session::put('msgType', 'danger');
			return Redirect::action('MonthController@showMonth', array('year' => date('Y'), 
                                                                   'month' => date('m')));
		}
		
		// at first get all data
		$schedule = Schedule::find($id);
		
		if (is_null($schedule)) {
			Session::put('message', Config::get('messages_de.task-doesnt-exist'));
            Session::put('msgType', 'danger');
			return Redirect::back();
		}

		// log the action
		Log::info('Delete task: User ' . Session::get('userId') . ' with rigths ' . Session::get('userGroup') . ' deletes task ' . $schedule->schdl_title . ' with id ' . $schedule->id . '.');
		
		$entries = $schedule->getEntries()->GetResults();
		
		// at least delete date in reverse order 'cause of dependencies in database
		foreach ($entries as $entry)
			$entry->delete();
		$schedule->delete();
		
		// show current month
		Session::put('message', Config::get('messages_de.delete-task-ok'));
        Session::put('msgType', 'success');
		return Redirect::action('ScheduleController@showTaskList');
	}
	
	// ---------------private functions ---------------------------------------	
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
		
		$schedule->schdl_title = Input::get('title');
		
		$schedule->schdl_time_preparation_start = null;
		
		return $schedule;
	}

	
}