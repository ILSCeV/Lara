<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $table ='survey_answer';
    protected $fillable = array('name', 'club');

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
        return $this->belongsTo('Lara\Person', 'prsn_id', 'id');
    }

    /**
     * Get the corresponding answerCells.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getAnswerCells()
    {
        return $this->hasMany('Lara\SurveyAnswerCell');
    }

}
