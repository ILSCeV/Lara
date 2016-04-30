<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table ='survey';
    protected $fillabe = array('deadline');
    
    public function getQuestions()
    {
        return $this->hasMany('App\SurveyQuestion');
    }

    public function getPerson()
    {
        return $this->belongsTo('App\Person', 'prsn_id', 'id');
    }
}
