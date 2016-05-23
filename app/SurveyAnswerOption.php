<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswerOption extends Model
{
    protected $table ='survey_question';
    protected $fillable = array('content');

    /**
     * Get the corresponding question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getQuestion()
    {
        return $this->belongsTo('Lara\SurveyQuestion');
    }
}
