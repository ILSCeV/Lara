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
        $club_ids = Section::all()->map(function(Section $section) {
            return $section->club()->id;
        });
        return Club::query()->whereIn('id',$club_ids);
    }

    public function section()
    {
        return (new Section)->where('title', $this->clb_title)->first();
    }
}
