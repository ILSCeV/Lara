<?php
/**
 * Das sind "Orte".
 */
 
class Place extends Eloquent {	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'places';
	/**
	 * The database columns used by the model.
	 *
	 * @var array
	 */
	protected $fillable = array('plc_title');
	
	/**
	 * Get the corresponding club events.
	 * Looks up in table club_events for entries, which have the same plc_id like id of Place instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\HasMany of type ClubEvent
	 */
	public function getClubEvent() {
		return $this->hasMany('ClubEvent', 'plc_id', 'id');
	}
}
