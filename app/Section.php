<?php

namespace Lara;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Representation of a section, like bc-club
 *
 * @property string title
 * @property string color
 * @property string section_uid
 * @property bool is_name_private
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
        'endTime',
        'is_name_private'
    ];

	/**
	 * Get the corresponding club events.
	 * Looks up in table club_events for entries, which have the same plc_id like id of Section instance.
	 *
	 * @return HasMany|ClubEvent
	 */
	public function getClubEvent() {
		return $this->hasMany(ClubEvent::class, 'plc_id', 'id');
	}

	/** @return Section|object*/
	public static function current() {
        $user = Auth::user();

        if (!$user) {
            return (object)[
                "title" => ""
            ];
        }
        return $user->section;
    }


    /**
     * @return Club|null|object|static
     */
    public function club()
    {
        return Club::where('clb_title', $this->title)->first();
    }

    /** @return HasMany|EventWarnConditions */
    public function eventWarnConditions()
    {
        return $this->hasMany(EventWarnConditions::class);
    }

    /**
     * @return HasMany|User
     */
    public function users()
    {
        return $this->hasMany('Lara\User');
    }

    public function templates()
    {
        return $this->hasMany('Lara\Template');
    }
}
