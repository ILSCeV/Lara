<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $table ='survey_question';
    protected $fillable = array('fieldType', 'content');
    
    public function getSurvey() 
    {
        return $this->belongsTo('App\Survey');
    }
    
    public function getAnswers() 
    {
        return $this->hasMany('App\SurveyAnswer', 'SurveyQuestion_number', 'number');
    }
}
