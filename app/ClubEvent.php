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
	protected $fillable = array('evnt_type',			// 0 -> default 
														// 1 -> info only 
														// 2 -> highlight / special
														// 3 -> live band / DJ / reading
														// 4 -> internal
														// 5 -> private party -> "Nutzung"
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
}
