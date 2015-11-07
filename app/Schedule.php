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
	protected $fillable = ['schdl_title', 
						   'schdl_time_preparation_start',
						   'schdl_due_date',
						   'schdl_password',
						   'evnt_id',			/* Old Lara 1.5 rule: if evnt_id = NULL then it's a "task", 
															  	  	  else it's a "schedule" for that ClubEvent */
						   'entry_revisons',
						   'schdl_is_template',
						   'schdl_show_in_week_view'];
}
