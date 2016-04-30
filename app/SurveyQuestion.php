<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $table ='survey_question';
    
    //many SurveyQuestions in one Survey possible
    public function survey()
    {
        return $this->belongsTo('App\Survey');
    }
    
    //one SurveyQuestion can have many SurveyAnswers
    public function answers()
    {
        return $this->hasMany('App\SurveyAnswer', 'SurveyQuestion_number', 'number');
    }

    
    public function nope()
    {
        return $this->hasMany('App\SurveyAnswer', 'SurveyQuestion_Survey_id', 'Survey_id');
    }

}
