<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table ='surveys';
    protected $fillable = array('title', 'description', 'deadline', 'password');

    /**
     * Get the corresponding person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getPerson()
    {
        return $this->belongsTo('Lara\Person', 'creator_id', 'id');
    }

    /**
     * Get the corresponding club.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getClub()
    {
        return $this->belongsTo('Lara\Club', 'club_id', 'id');
    }

    /**
     * Get the corresponding questions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getQuestions()
    {
        return $this->hasMany('Lara\SurveyQuestion');
    }


    /**
     * Get the corresponding Answers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getAnswers()
    {
        return $this->hasMany('Lara\SurveyAnswer');
    }
}
