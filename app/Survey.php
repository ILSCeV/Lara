<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table ='survey';
    protected $fillabe = array('deadline');
    
    public function getQuestions()
    {
        return $this->hasMany('Lara\SurveyQuestion');
    }

    public function getPerson()
    {
        return $this->belongsTo('Lara\Person', 'prsn_id', 'id');
    }
}
