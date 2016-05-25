<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswerOption extends Model
{
    protected $table ='survey_answer_options';
    protected $fillable = array('answer_option');

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
