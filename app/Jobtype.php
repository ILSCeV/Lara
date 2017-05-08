<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Jobtype extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'jobtypes';
	
	/**
	 * The database columns used by the model.
	 * This attributes are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('jbtyp_title', 
								'jbtyp_time_start',
								'jbtyp_time_end',
								'jbtyp_needs_preparation',
								'jbtyp_statistical_weight',
								'jbtyp_is_archived');

	/**
	 * Get the corresponding schedule entries.
	 * Looks up in table schedule_entries for entries, which have the same jbtyp_id like id of Jobtype instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\HasMany of type ClubEvent
	 */
	public function getJob(){
	  return $this->hasMany('Lara\Shift', 'jbtyp_id', 'id');
	}
}
