<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table ='survey';

    //one Survey can have many SurveyQuestions
    public function questions()
    {
        return $this->hasMany('App\SurveyQuestion');
    }
    
}
