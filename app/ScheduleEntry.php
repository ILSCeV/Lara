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
}
