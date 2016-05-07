<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $table ='survey_answer';
    protected $fillable = array('name', 'content');

    /**
     * Get the corresponding question.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getQuestion()
    {
        return $this->belongsTo('Lara\SurveyQuestion', 'survey_question_id', 'id');
    }

    /**
     * returns Survey of the answer, don't know if this works properly at the moment
     * 
     * @return mixed
     */
    public function getSurvey()
    {
        return $this->belongsTo('Lara\SurveyQuestion', 'survey_question_id', 'survey_id')->belongsTo('Lara\Survey');
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

}
