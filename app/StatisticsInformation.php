<?php

namespace Lara;


use Illuminate\Database\Eloquent\Model;

/**
 * @property int own_section
 * @property int other_section
 * @property double shifts_percent_intern
 * @property double shifts_percent_extern
 */
class StatisticsInformation extends Model
{

    protected $fillable = ['person_id', 'user_id', 'own_section', 'other_section', 'shifts_percent_intern', 'shifts_percent_extern','flood_shift'];

    protected $casts = ['shifts_percent_intern' => 'double', 'shifts_percent_extern' => 'double'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|Person
     */
    public function person()
    {
        return $this->hasOne(Person::class, 'id', 'person_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|User
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
