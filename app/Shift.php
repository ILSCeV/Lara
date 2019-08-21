<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
/**
 * @property Schedule schedule
 */
class Shift extends Model
{
    protected $table = 'shifts';

    protected $fillable = [
        'schedule_id',
        'shifttype_id',
        'person_id',
        'comment',
        'start',
        'end',
        'optional',
        'statistical_weight',
        'position'
    ];

    public function comment() {
        return $this->comment;
    }

    /**
     * Get the corresponding shift type.
     * Looks up in table shiftTypes for that entry, which has the same id like shifttype_id of ScheduleEntry instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|ShiftType
     */
    public function type() {
        return $this->belongsTo('Lara\ShiftType', 'shifttype_id', 'id');
    }

    /**
     * Get the corresponding person, if existing.
     * Looks up in table persons for that entry, which has the same id like person_id of ScheduleEntry instance.
     * If prsn_is is null, also null will be returned.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Person
     */
    public function getPerson() {
        return $this->belongsTo('Lara\Person', 'person_id', 'id');
    }

    /**
     * Get the corresponding person, if existing.
     * Looks up in table persons for that entry, which has the same id like person_id of ScheduleEntry instance.
     * If prsn_is is null, also null will be returned.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Person
     */
    public function person() {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }

    /**
     * Get the corresponding schedule.
     * Looks up in table schedule for that entry, which has the same id like schedule_id of ScheduleEntry instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Schedule
     */
    public function getSchedule() {
        return $this->belongsTo('Lara\Schedule', 'schedule_id', 'id');
    }

    /**
     * Get quantity of shifts grouped by $shiftTypeId.
     *
     * @param Collection|Shift $shifts
     * @param int $shiftTypeId
     * @return int quantity of $shifts grouped by $shiftTypeId
     */
    public static function countGroupedBy($shifts, $shiftTypeId)
    {
        $count = 0;

        if (!is_null($shifts))
        {
            foreach($shifts as $shift)
            {
                if ($shift->shifttype_id == $shiftTypeId)
                    $count++;
            }
        }

        return $count;
    }

    /**
     * Get start time of a shift which has $shiftTypeId.
     *
     * @param Collection $shifts
     * @param int $shiftTypeId
     * @return time the start time
     */
    public static function getTimeStart($shifts, $shiftTypeId)
    {
        if (!is_null($shifts))
        {
            foreach($shifts as $shift)
            {
                if ($shift->shifttype_id == $shiftTypeId)
                    return $shift->start;
            }
        }

        return null;
    }

    /**
     * Get end time of a shift which has $shiftTypeId.
     *
     * @param Collection $shifts
     * @param int $shiftTypeId
     * @return time the end time
     */
    public static function getTimeEnd($shifts, $shiftTypeId)
    {
        if (!is_null($shifts))
        {
            foreach($shifts as $shift)
            {
                if ($shift->shifttype_id == $shiftTypeId)
                    return $shift->end;
            }
        }

        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Schedule
     */
    public function schedule()
    {
        return $this->belongsTo('Lara\Schedule', 'schedule_id', 'id');
    }

    public static function sortByOrder($shifts) {
    }
}
