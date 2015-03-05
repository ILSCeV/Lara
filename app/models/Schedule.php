<?php

use Illuminate\Database\Eloquent\Collection as Collection; 
 
/**
 * Das sind "Dienstpläne".
 */

class Schedule extends Eloquent {	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'schedules';
	/**
	 * The database columns used by the model.
	 *
	 * @var array
	 */
	protected $fillable = array('schdl_title', 
								'schdl_time_preparation_start',
								'schdl_due_date',
								'schdl_password',
								'evnt_id',		/* if NULL then it's a "task", else it's a "schedule" for that ClubEvent */
								'schdl_is_template',
								'schdl_show_in_week_view');
	
	/**
	 * Get the corresponding club event, if existing.
	 * Looks up in table club_events for that entry, which has the same id like evnt_id of Schedule instance.
	 * If there is no entry, null will be returned.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\BelongsTo of type ClubEvent
	 */
	public function getClubEvent() {
		return $this->belongsTo('ClubEvent', 'evnt_id', 'id');
	}

	/**
	 * Get the corresponding schedule entries.
	 * Looks up in table schedule_entries for those entries, which has the same schdl_id like id of schedule instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\HasMany of type ScheduleEntry
	 */	
	public function getEntries() {
		return $this->hasMany('ScheduleEntry', 'schdl_id', 'id');
	}	
	
	/**
	* Get names of jobtypes, which belongs to the schedule.
	*
	* @return string[] $jobNames
	*/
	public function getTemplateEntries() 
	{
		$jobNames = new Collection;
		
		$entries = $this->getEntries()->get();
		foreach($entries as $entry)
		{
			$jobNames->add($entry->getJobType->jbtyp_title);
		}
		return $jobNames;
	}	
}
