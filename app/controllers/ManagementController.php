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

class ManagementController extends BaseController {


	/**
	 * Generates the view with all existing job types and allows editing
	 *
	 * @return view jobManagementView
	 */
	public function showJobTypes(){

		$jobtypes = Jobtype::orderBy('jbtyp_title', 'ASC')->get();

		return View::make('jobManagementView', compact('jobtypes'));
	}

	/**
	 * Updates all existing job types with changes provided.
	 *
	 * @return view jobManagementView
	 */
	public function updateJobTypes(){

		$jobtypes = Jobtype::orderBy('jbtyp_title', 'ASC')->get();

		foreach ($jobtypes as $jobtype) 
		{
			$jobtype->jbtyp_title 				=	Input::get('jbtyp_title' . $jobtype->id); 

			$jobtype->jbtyp_time_start 			=	Input::get('jbtyp_time_start' . $jobtype->id); 

			$jobtype->jbtyp_time_end 			=	Input::get('jbtyp_time_end' . $jobtype->id); 

			$jobtype->jbtyp_statistical_weight 	= 	Input::get('jbtyp_statistical_weight' . $jobtype->id); 

			$jobtype->jbtyp_is_archived 		=	Input::get('jbtyp_is_archived' . $jobtype->id); 

			/*if (Input::get('destroy' . $jobtype->id)) {
				//ToDo: need to find all schedules that use it and clean them here first.
				$jobtype->delete(); 
			} */

			$jobtype->save();
		}

		return View::make('jobManagementView', compact('jobtypes'));
	}


	/**
	 * Generates the view with all existing places and allows editing
	 *
	 * @return view placeManagementView
	 */
	public function showPlaces(){

		$places = Place::orderBy('plc_title', 'ASC')->get();

		return View::make('placeManagementView', compact('places'));
	}


	/**
	 * Updates all existing places with changes provided.
	 *
	 * @return view placeManagementView
	 */
	public function updatePlaces(){

		$places = Place::orderBy('plc_title', 'ASC')->get();

		foreach ($places as $place) 
		{
			if (Input::get('destroy' . $place->id)) {

				// find all schedules that use this place and replace it with a placeholder
				$filter = ClubEvent::where('plc_id','=',$place->id)->get();

				foreach ($filter as $event) {		
					$event->plc_id = 0; // placeholder with plc_title "-"
					$event->save();
				}

				Place::destroy($place->id);

			} else {

				// update title
				$place->plc_title =	Input::get('plc_title' . $place->id); 
				$place->save();

			}
			
		}

		// need to update our index after the changes
		$places = Place::orderBy('plc_title', 'ASC')->get();

		return View::make('placeManagementView', compact('places'));
	}	

}

