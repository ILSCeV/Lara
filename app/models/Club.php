<?php
/**
 * Das sind "Vereine"
 */
 
class Club extends Eloquent {	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'clubs';
	/**
	 * The database columns used by the model.
	 *
	 * @var array
	 */
	protected $fillable = array('clb_title');
	
	/**
	 * Get the corresponding persons.
	 * Looks up in table persons for entries, which have the same clb_id like id of Club instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\HasMany of type Person
	 */
	public function getPerson() {
		return $this->hasMany('Person', 'clb_Id', 'id');
	}
}
