<?php

namespace Lara;

use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Representation of a section, like bc-club
 *
 * @property string title
 * @property string color
 * @property string section_uid
 */
class Section extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sections';

	/**
	 * The database columns used by the model.
	 * This attributes are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
        'title',
        'section_uid',
        'color',
        'preparationTime',
        'startTime',
        'endTime'
    ];

	/**
	 * Get the corresponding club events.
	 * Looks up in table club_events for entries, which have the same plc_id like id of Section instance.
	 *
	 * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\HasMany of type ClubEvent
	 */
	public function getClubEvent() {
		return $this->hasMany('Lara\ClubEvent', 'plc_id', 'id');
	}

	public static function current() {
        $user = Auth::user();

        if (!$user) {
            return [
                "title" => ""
            ];
        }
        return $user->section;
    }


    public function club()
    {
        return Club::where('clb_title', $this->title)->first();
    }

    public function users()
    {
        return $this->hasMany('Lara\User');
    }
}
