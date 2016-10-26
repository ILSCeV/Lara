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

    public static function make($question, $text)
    {
        $option = new SurveyAnswerOption();
        $option->survey_question_id = $question->id;
        $option->answer_option = $text;
        $option->save();
        return $option;
    }
}
