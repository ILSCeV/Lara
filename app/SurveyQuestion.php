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
     * Get the corresponding answerOptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getAnswerOptions()
    {
        return $this->hasMany('Lara\SurveyAnswerOption');
    }
}
