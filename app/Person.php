<?php

namespace Lara;

use Auth;
use Illuminate\Support\Facades\Gate;
use Session;
use Illuminate\Database\Eloquent\Model;

use Lara\Status;
/**
 * @property string prsn_name
 * @property User user
*/
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
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo/Lara\Club
	 */
    public function getClub() {
        return $this->belongsTo('Lara\Club', 'clb_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Club
     */
    public function club() {
        return $this->belongsTo(Club::class, 'clb_id', 'id');
    }

    public function name()
    {
        return $this->prsn_name;
    }

    public function isLoggedInUser()
    {
        return $this->prsn_ldap_id == Auth::user()->person->prsn_ldap_id;
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
        $shortHand = Status::shortHand($this->prsn_status);
        return $shortHand ? "(" . $shortHand . ")" : "";
    }

    /**
     * @return \Lara\User
     */
    public function user()
    {
        $userRelationship = $this->hasOne(User::class);
        return $userRelationship->exists() ? $userRelationship->first() : User::createFromPerson($this);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|User
     */
    public function getUser()
    {
       return $this->hasOne(User::class);
    }

    public static function isCurrent($ldap_id)
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        return $user->person->prsn_ldap_id === $ldap_id;
    }

    public function fullName()
    {
        $user = $this->user();

        if ($user && Gate::allows('accessInformation', $user)) {
            return $user->fullName();
        }

        return "";
    }
}
