<?php

namespace Lara;

class SurveyAnswer extends BaseSoftDelete
{
    protected $table ='survey_answers';
    protected $fillable = array('name', 'order');

    /**
     * Get the corresponding question.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getSurvey()
    {
        return $this->belongsTo('Lara\Survey', 'survey_id', 'id');
    }

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
     * Get the corresponding SurveyAnswerCells.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getAnswerCells()
    {
        return $this->hasMany('Lara\SurveyAnswerCell');
    }

    /**
     * Get the corresponding Revision_SurveryAnswers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getRevision_SurveyAnswers()
    {
        return $this->hasMany('Lara\Revision_SurveyAnswer', 'object_id', 'id');
    }

}
