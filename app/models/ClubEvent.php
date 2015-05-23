<?php
/**
 * DO NOT CALL THIS CLASS "EVENT"
 * this name is reserved for Laravel events and will cause an error. 
 *
 * Das sind "Veranstaltungen".
 *
 */
 
class ClubEvent extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'club_events';
	/**
	 * The database columns used by the model.
	 *
	 * @var array
	 */
	protected $fillable = array('evnt_type',			    // 0 -> default 
															// 1 -> info only 
															// 2 -> highlight -> special
															// 3 -> live band/DJ/reading
															// 4 -> internal
															// 5 -> private party -> Nutzung
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
		return $this->belongsTo('Place', 'plc_id', 'id');
	}
	
	/**
	 * Get the corresponding schedule.
	 * Looks up in table schedules for that entry, which has the same evnt_id like id of ClubEvent instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\HasOne of type Schedule
	 */
	public function getSchedule() {
		return $this->hasOne('Schedule', 'evnt_id', 'id');
	}
	
	
	
}
