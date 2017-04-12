<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class ScheduleEntry extends Model
{
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'schedule_entries';

	/**
	 * The database columns used by the model.
	 * This attributes are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['schdl_id', 
						   'jbtyp_id',
						   'prsn_id',
						   'entry_user_comment',
						   'entry_time_start',
						   'entry_time_end',
						   'entry_statistical_weight'];

	
	/**
	 * Get the corresponding job type.
	 * Looks up in table jobtypes for that entry, which has the same id like jbtyp_id of ScheduleEntry instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\BelongsTo of type Jobtype
	 */	
	public function getJobType() {
        return $this->belongsTo('Lara\Jobtype', 'jbtyp_id', 'id');
    }

	/**
	 * Get the corresponding job type.
	 * Looks up in table jobtypes for that entry, which has the same id like jbtyp_id of ScheduleEntry instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\BelongsTo of type Jobtype
	 */
	public function jobType() {
		return $this->belongsTo('Lara\Jobtype', 'jbtyp_id', 'id');
	}
	
	/**
	 * Get the corresponding person, if existing.
	 * Looks up in table persons for that entry, which has the same id like prsn_id of ScheduleEntry instance.
	 * If prsn_is is null, also null will be returned.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\BelongsTo of type Person
	 */
	public function getPerson() {
        return $this->belongsTo('Lara\Person', 'prsn_id', 'id');
    }
	
	/**
	 * Get the corresponding schedule.
	 * Looks up in table schedule for that entry, which has the same id like schdl_id of ScheduleEntry instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\BelongsTo of type Schedule
	 */
	public function getSchedule() {
        return $this->belongsTo('Lara\Schedule', 'schdl_id', 'id');
    }

	/**
	 * Get quantity of $entries grouped by $jobtypeId.
	 * 
	 * @param Collection $entries
	 * @param int $jobtypeId
	 * @return int quantity of $entries grouped by $jobtypeId
	 */
	public static function countGroupedBy($entries, $jobtypeId)
	{
		$count = 0;
		
		if (!is_null($entries))
		{
			foreach($entries as $entry)
			{
				if ($entry->jbtyp_id == $jobtypeId)
					$count++;
			}
		}	
			
		return $count;
	}
	
	/**
	 * Get start time of a scheduleEntry which has $jobtypeId.
	 *
	 * @param Collection $entries
	 * @param int $jobtypeId
	 * @return time the start time
	 */
	public static function getTimeStart($entries, $jobtypeId)
	{
		if (!is_null($entries))
		{
			foreach($entries as $entry)
			{
				if ($entry->jbtyp_id == $jobtypeId)
					return $entry->entry_time_start;
			}
		}	
			
		return null;
	}

	/**
	 * Get end time of a scheduleEntry which has $jobtypeId.
	 *
	 * @param Collection $entries
	 * @param int $jobtypeId
	 * @return time the end time
	 */
	public static function getTimeEnd($entries, $jobtypeId)
	{
		if (!is_null($entries))
		{
			foreach($entries as $entry)
			{
				if ($entry->jbtyp_id == $jobtypeId)
					return $entry->entry_time_end;
			}
		}	
			
		return null;
	}

	public function schedule()
    {
        return $this->belongsTo('Lara\Schedule', 'schdl_id', 'id');
    }

    public function event()
    {
        return $this->schedule()->event();
    }

	public function start()
    {
        return $this->event()->evnt_date_start();
    }

    public function end()
    {
        return $this->event()->evnt_date_end();
    }

}
