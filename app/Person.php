<?php

namespace Lara;

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
								'clb_id');
}
