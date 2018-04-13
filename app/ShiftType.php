<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class ShiftType extends Model
{
    protected $table = 'shifttypes';

    protected $fillable = [
        'title',
        'start',
        'end',
        'needs_preparation',
        'statistical_weight',
        'is_archived'
    ];

    /**
     * Get the corresponding shifts.
     * Looks up in table shifts for shifts, which have the same shifttype_id like id of ShiftType instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Shift of type ClubEvent
     */
    public function getJob(){
        return $this->hasMany('Lara\Shift', 'shifttype_id', 'id');
    }

    /**
     * Get the corresponding shifts.
     * Looks up in table shifts for shifts, which have the same shifttype_id like id of ShiftType instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Shift of type ClubEvent
     */
    public function shifts(){
        return $this->hasMany('Lara\Shift', 'shifttype_id', 'id');
    }

    public function title() {
        return $this->title;
    }

    public function start() {
        return $this->start;
    }

    public function end() {
        return $this->end;
    }

    public function needsPreparation() {
        return $this->needs_preparation;
    }

    public function weight() {
        return $this->statistical_weight;
    }

    public function isArchived() {
        return $this->is_archived;
    }
}
