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
        return $this->persons()->whereNotNull('prsn_ldap_id')->where('prsn_ldap_id','<>','9999');
    }

    public static function activeClubs()
    {
        $sectionTitles = Section::all()->pluck('title')->toArray();
        $clubs = Club::whereIn('clb_title', $sectionTitles)->get()->filter(function (Club $club) use ($sectionTitles) {
            return in_array($club->clb_title, $sectionTitles);
        });
        return Club::whereIn('id',$clubs->map(function (Club $club){ return $club->id; })->toArray());
    }

}
