<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $table ='survey_answer';
    protected $fillable = array('name', 'content');
    
    public function getQuestion() 
    {
        return $this->belongsTo('Lara\SurveyQuestion', 'survey_question_number', 'number');
    }

    //returns Survey of the answer, don't know if this works properly at the moment
    public function getSurvey() 
    {
        return $this->belongsTo('Lara\SurveyQuestion', 'survey_question_id', 'survey_id')->belongsTo('Lara\Survey');
    }
    
    public function getPerson()
    {
        return $this->belongsTo('Lara\Person', 'prsn_id', 'id');
    }

}
