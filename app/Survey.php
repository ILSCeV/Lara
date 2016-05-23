<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table ='survey';
    protected $fillable = array('title', 'description', 'deadline', 'in_calendar');

    /**
     * Get the corresponding person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getPerson()
    {
        return $this->belongsTo('Lara\Person', 'prsn_id', 'id');
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
