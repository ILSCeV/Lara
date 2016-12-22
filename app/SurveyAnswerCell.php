<?php

namespace Lara;

class SurveyAnswerCell extends BaseSoftDelete
{
    protected $table ='survey_answer_cells';
    protected $fillable = array('answer');

    /**
 * Get the corresponding Answer.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
 */
    public function getAnswer()
    {
        return $this->belongsTo('Lara\SurveyAnswer', 'survey_answer_id', 'id');
    }

    /**
     * Get the corresponding Question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getQuestion()
    {
        return $this->belongsTo('Lara\SurveyQuestion', 'survey_question_id', 'id');
    }

    /**
     * Get the corresponding Answer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function answer()
    {
        return $this->belongsTo('Lara\SurveyAnswer', 'survey_answer_id', 'id');
    }

    /**
     * Get the corresponding Question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo('Lara\SurveyQuestion', 'survey_question_id', 'id');
    }
}
