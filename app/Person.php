<?php

namespace Lara;

use Session;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'persons';

	/**
	 * The database columns used by the model.
	 * This attributes are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('prsn_name', 
								'prsn_ldap_id',
								'prsn_status',
								'clb_id',
                                'prsn_uid');

	/**
	 * Get the corresponding club.
	 * Looks up in table club for that entry, which has the same id like clb_id of Person instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\BelongsTo of type Club
	 */
    public function getClub() {
        return $this->belongsTo('Lara\Club', 'clb_id', 'id');
    }

    public function club() {
        return $this->belongsTo('Lara\Club', 'clb_id', 'id');
    }

    public function name()
    {
        return $this->prsn_name;
    }

    public function isLoggedInUser() {
        return $this->prsn_ldap_id == Session::get('userId');
    }
}
