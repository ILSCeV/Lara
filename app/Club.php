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

    public function persons()
    {
        return $this->hasMany('Lara\Person', 'clb_id');
    }

    public function members()
    {
        return $this->persons()->where('prsn_status', '=', 'member');
    }

    public function candidates()
    {
        return $this->persons()->where('prsn_status', '=', 'candidate');
    }

    public function accountableForStatistics()
    {
        return $this->persons()->whereIn('prsn_status', ['member', 'candidate', 'veteran']);
    }

    public static function activeClubs()
    {
        return Club::whereIn('clb_title', Place::all()->pluck('plc_title'));
    }

}