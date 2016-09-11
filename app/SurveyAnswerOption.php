<?php

namespace Lara;

class SurveyAnswerOption extends BaseSoftDelete
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

    /**
     * Get the corresponding question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo('Lara\SurveyQuestion');
    }
}
