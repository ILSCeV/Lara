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

    public function isLoggedInUser()
    {
        return $this->prsn_ldap_id == Session::get('userId');
    }

    public function nameWithStatus()
    {
        return $this->prsn_name .  " " . $this->shortHand();
    }

    public function shifts()
    {
        return $this->hasMany('Lara\Shift', 'person_id');
    }

    public function lastShift()
    {
        return $this->shifts()->orderBy('updated_at', 'desc')->first();
    }

    public function shortHand()
    {
        switch ($this->prsn_status) {
            case "candidate":
                return "(K)";
            case "member":
                return "(A)";
            case "veteran":
                return "(V)";
            default:
                return "";
        }
    }

    public function user()
    {
        $userRelationship = $this->hasOne('Lara\User');
        return $userRelationship->exists() ? $userRelationship->first() : User::createFromPerson($this);
    }
}
