<?php

/**
 * DO NOT CALL THIS CLASS "EVENT"
 * That name is reserved for Laravel events and will cause an error.
 *
 */

namespace Lara;

use Illuminate\Database\Eloquent\Model;
/**
 * @property int evnt_type
 * @property string evnt_title
 * @property string evnt_subtitle
 * @property \DateTime evnt_date_start
 * @property \DateTime evnt_date_end
 * @property \DateTime evnt_time_start
 * @property \DateTime evnt_time_end
 * @property string external_id
 */
class ClubEvent extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var $table string
	 */
	protected $table = 'club_events';
	
	/**
	 * The database columns used by the model.
	 * This attributes are mass assignable.
	 *
	 * @var $fillable array
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
								'evnt_title',
								'evnt_subtitle',
								'plc_id',
								'evnt_date_start',
								'evnt_date_end',
								'evnt_time_start',
								'evnt_time_end',
								'evnt_public_info',
								'evnt_private_details',
								'evnt_is_private',
								'evnt_is_published',
                                'external_id');

	/**
	 * Get the corresponding section.
	 * Looks up in table sections for that entry, which has the same id like plc_id of ClubEvent instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\BelongsTo of type Section
	 */
	public function section() {
        return $this->belongsTo('Lara\Section', 'plc_id', 'id');
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

	public function schedule() {
        return $this->hasOne('Lara\Schedule', 'evnt_id', 'id');
    }

    /**
     * Check if the signed in user has a shift in the current event
     * Looks up in table Shifts for entries, which has the same scheduleId like id of ClubEvent instance.
     * This funtion is only used for highlighting the event in month view if the signed in user has a shift in it.
     *
     * @return Boolean
     */
    public function hasShift($scheduleId, $userId) {

        $shifts = Shift::where('schedule_id', '=', $scheduleId)->with('getPerson')->get();
        foreach($shifts as $shift) {
            if ( isset($shift->getPerson->prsn_ldap_id) && $shift->getPerson->prsn_ldap_id ==  $userId){
                return true;
            }
        }
        return false;
    }

	public function shifts() {
		return $this->hasManyThrough('Lara\Shift', 'Lara\Schedule', 'evnt_id', 'schedule_id', 'id');
	}
	
	public function showToSection() {
        return $this->belongsToMany('Lara\Section');
    }
    
    public function showToSectionNames() {
        return $this->showToSection()->get()->map(function($section) {
            return $section->title;
        })->toArray();
    }
}
