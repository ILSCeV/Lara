<?php

/**
 * DO NOT CALL THIS CLASS "EVENT"
 * That name is reserved for Laravel events and will cause an error. 
 *
 */

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class ClubEvent extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'club_events';
	
	/**
	 * The database columns used by the model.
	 * This attributes are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('evnt_type',			// 0 -> default -> "normales Programm"
														// 1 -> info only 
														// 2 -> highlight / special
														// 3 -> live band / DJ / reading
														// 4 -> internal event
														// 5 -> private party -> "Nutzung"
														// 6 -> cleaning -> "Fluten"
														// 7 -> flyer / poster
														// 8 -> tickets -> "Vorverkauf"
														// 9 -> internal task -> everything else
														// 10 -> survey -> "Umfrage"
								'evnt_title', 
								'evnt_subtitle',
								'plc_id',
								'evnt_date_start',
								'evnt_date_end',
								'evnt_time_start',
								'evnt_time_end',
								'evnt_public_info',		
								'evnt_private_details',
								'evnt_is_private');

	/**
	 * Get the corresponding place.
	 * Looks up in table places for that entry, which has the same id like plc_id of ClubEvent instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\BelongsTo of type Place
	 */
	public function getPlace() {
		return $this->belongsTo('Lara\Place', 'plc_id', 'id');
	}
	
	/**
	 * Get the corresponding schedule.
	 * Looks up in table schedules for that entry, which has the same evnt_id like id of ClubEvent instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\HasOne of type Schedule
	 */
	public function getSchedule() {
		return $this->hasOne('Lara\Schedule', 'evnt_id', 'id');
	}
}
