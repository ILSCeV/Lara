<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $table ='survey_answer';
    protected $fillable = array('name', 'content');
    
    public function getQuestion() 
    {
        return $this->belongsTo('App\SurveyQuestion', 'SurveyQuestion_number', 'number');
    }

    //returns Survey of the answer, don't know if this works properly at the moment
    public function getSurvey() 
    {
        return $this->belongsTo('App\SurveyQuestion', 'SurveyQuestion_Survey_id', 'Survey_id')->belongsTo('App\Survey');
    }
    
    public function getPerson()
    {
        return $this->belongsTo('App\Person', 'prsn_id', 'id');
    }

}
