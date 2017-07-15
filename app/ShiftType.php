<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class ShiftType extends Model
{
    protected $table = 'shifttypes';

    protected $fillable = [
        'jbtyp_title',
        'jbtyp_time_start',
        'jbtyp_time_end',
        'jbtyp_needs_preparation',
        'jbtyp_statistical_weight',
        'jbtyp_is_archived'
    ];

    /**
     * Get the corresponding shifts.
     * Looks up in table shifts for shifts, which have the same shifttype_id like id of ShiftType instance.
     *
     * @return \vendor\laravel\framework\src\Illuminate\Database\Eloquent\Relations\HasMany of type ClubEvent
     */
    public function getJob(){
        return $this->hasMany('Lara\Shift', 'shifttype_id', 'id');
    }

    public function title() {
        return $this->jbtyp_title;
    }

    public function start() {
        return $this->jbtyp_time_start;
    }

    public function end() {
        return $this->jbtyp_time_end;
    }

    public function needsPreparation() {
        return $this->jbtyp_needs_preparation;
    }

    public function weight() {
        return $this->jbtyp_statistical_weight;
    }

    public function isArchived() {
        return $this->jbtyp_is_archived;
    }
}
