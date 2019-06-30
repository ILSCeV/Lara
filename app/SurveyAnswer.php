<?php

namespace Lara;

class SurveyAnswer extends BaseSoftDelete
{
    protected $table ='survey_answers';
    protected $fillable = array('name', 'order');

    /**
     * Get the corresponding question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Survey
     */
    public function getSurvey()
    {
        return $this->belongsTo('Lara\Survey', 'survey_id', 'id');
    }

    /**
     * Get the corresponding person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Person
     */
    public function getPerson()
    {
        return $this->belongsTo(Person::class, 'creator_id', 'id');
    }

    /**
     * Get the corresponding club.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Club
     */
    public function getClub()
    {
        return $this->belongsTo('Lara\Club', 'club_id', 'id');
    }

    /**
     * Get the corresponding SurveyAnswerCells.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|SurSurveyAnswerCellvey
     */
    public function getAnswerCells()
    {
        return $this->hasMany('Lara\SurveyAnswerCell');
    }

    /**
     * Get the corresponding question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Survey
     */
    public function survey()
    {
        return $this->belongsTo('Lara\Survey', 'survey_id', 'id');
    }

    /**
     * Get the corresponding person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Person
     */
    public function person()
    {
        return $this->belongsTo('Lara\Person', 'creator_id', 'id');
    }

    /**
     * Get the corresponding club.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Club
     */
    public function club()
    {
        return $this->belongsTo('Lara\Club', 'club_id', 'id');
    }
    /** * Get the corresponding SurveyAnswerCells.  * * @return \Illuminate\Database\Eloquent\Relations\HasMany|SurveyAnswerCell */
    public function cells()
    {
        return $this->hasMany('Lara\SurveyAnswerCell');
    }

    public function section() 
    {
        return $this->survey->section();
    }
}
