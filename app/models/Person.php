<?php


/**
 * Das sind einzelne Personen.
 */
 
class Person extends Eloquent {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'persons';
	/**
	 * The database columns used by the model.
	 *
	 * @var array
	 */
	protected $fillable = array('prsn_name', 
								'prsn_ldap_id',
								'prsn_status',
								'clb_id');
	
	/**
	 * Get the corresponding club.
	 * Looks up in table club for that entry, which has the same id like clb_id of Person instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\BelongsTo of type Club
	 */
    public function getClub() {
        return $this->belongsTo('Club', 'clb_id', 'id');
    }
}
