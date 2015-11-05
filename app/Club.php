<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'clubs';

	/**
	 * The database columns used by the model.
	 * This attributes are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('clb_title');
}
