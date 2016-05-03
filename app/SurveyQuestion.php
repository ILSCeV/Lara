<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $table ='survey_question';
    protected $fillable = array('fieldType', 'content');

    /**
     * Get the corresponding survey.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getSurvey() 
    {
        return $this->belongsTo('Lara\Survey');
    }

    /**
     * Get the corresponding answers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getAnswers() 
    {
        return $this->hasMany('Lara\SurveyAnswer', 'number', 'survey_question_number');
    }
}
