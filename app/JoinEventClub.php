<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;
/**
 * Join Table to connect Club and ClubEvent
 *
 * @property int club_id
 * @property int event_id
 */
class JoinEventClub extends Model
{
    /**
     * The database table used by the model.
     *
     * @var $table string
     */
    protected $table = 'join_events_to_club';
    
    /**
     * The database columns used by the model.
     * This attributes are mass assignable.
     *
     * @var $fillable array
     */
    protected $fillable = array('club_id',
                           'event_id');
    
    /** disables the timestamps */
    public $timestamps = false;
}
