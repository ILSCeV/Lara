<?php

namespace Lara;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon warn_date_start
 * @property Carbon warn_date_end
 * @property Carbon warn_time_start
 * @property Carbon warn_time_end
 * @property String reason
*/
class EventWarnConditions extends Model
{
    protected $fillable = [
        'warn_date_start',//
        'warn_date_end', //
        'warn_time_start', //
        'warn_time_end', //
        'reason'
    ];

    protected $dates = [
        'warn_date_start',//
        'warn_date_end', //
        'warn_time_start', //
        'warn_time_end', //
    ];

    protected $casts = [
        'warn_time_start' => 'datetime:H:i', //
        'warn_time_end'=> 'datetime:H:i', //
    ];
}
