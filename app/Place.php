<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'places';

	/**
	 * The database columns used by the model.
	 * This attributes are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('plc_title', 'place_uid');

	/**
	 * Get the corresponding club events.
	 * Looks up in table club_events for entries, which have the same plc_id like id of Place instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\HasMany of type ClubEvent
	 */
	public function getClubEvent() {
		return $this->hasMany('Lara\ClubEvent', 'plc_id', 'id');
	}
}
