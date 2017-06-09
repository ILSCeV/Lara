<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schedules';

    /**
     * The database columns used by the model.
     * This attributes are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'schdl_title',
        'schdl_time_preparation_start',
        'schdl_due_date',
        'schdl_password',
        'evnt_id',            /* Old Lara 1.5 rule: if evnt_id = NULL then it's a "task",
															  	  	  else it's a "schedule" for that ClubEvent */
        'entry_revisons',
        'schdl_is_template',
        'schdl_show_in_week_view'
    ];

    /**
     * Get the corresponding club event, if existing.
     * Looks up in table club_events for that entry, which has the same id like evnt_id of Schedule instance.
     * If there is no entry, null will be returned.
     *
     * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\BelongsTo of type ClubEvent
     */
    public function getClubEvent()
    {
        return $this->belongsTo('Lara\ClubEvent', 'evnt_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo('Lara\ClubEvent', 'evnt_id', 'id');
    }

	public function shifts() {
	    return $this->hasMany('Lara\Shift', 'schedule_id', 'id');
    }
	
	/**
	* Get names of jobtypes, which belongs to the schedule.
	*
	* @return string[] $jobNames
	*/
	public function getTemplateEntries() 
	{
		$jobNames = new Collection;
		
		$entries = $this->shifts()->get();
		foreach($entries as $entry)
		{
			$jobNames->add($entry->getJobType->jbtyp_title);
		}
		return $jobNames;
	}	
}
