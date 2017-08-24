<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function accountableForStatistics(Carbon $date)
    {
        $lowerLimit = $date->copy()->subMonth(3);
        $upperLimit = $date->copy()->addMonth(1);
        return $this->persons()->where(function($query) use ($lowerLimit, $upperLimit) {
            $query->whereIn('prsn_status', ['member', 'candidate'])
                ->orWhere(function ($query)  use($lowerLimit, $upperLimit) {
                    $query->whereHas('shifts', function ($query) use ($lowerLimit, $upperLimit) {
                        $query->whereDate('updated_at', '>=', $lowerLimit)
                            ->whereDate('updated_at', '<=', $upperLimit);
                    });
                });
        });
    }

    public static function activeClubs()
    {
        return Club::whereIn('clb_title', Section::all()->pluck('title'));
    }

}
