<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $table ='survey_answer';

    //many SurveyAnswers in one SurveyQuestion possible
    public function questionNumber()
    {
        return $this->belongsTo('App\SurveyQuestion', 'SurveyQuestion_number', 'number');
    }

    //return Survey of the answer, don't know if this works properly at the moment
    public function survey()
    {
        return $this->belongsTo('App\SurveyQuestion', 'SurveyQuestion_Survey_id', 'Survey_id')->belongsTo('App\Survey');
    }

}
