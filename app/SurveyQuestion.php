<?php

namespace Lara;

class SurveyQuestion extends BaseSoftDelete
{
    protected $table ='survey_questions';
    protected $fillable = array('field_type', 'question', 'order');

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

    /**
     * Get the corresponding answerCells.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getAnswerCells()
    {
        return $this->hasMany('Lara\SurveyAnswerCell');
    }

    /**
     * Get the corresponding survey.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function survey()
    {
        return $this->belongsTo('Lara\Survey');
    }

    /**
     * Get the corresponding answerOptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany('Lara\SurveyAnswerOption');
    }

    /**
     * Get the corresponding answerCells.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cells()
    {
        return $this->hasMany('Lara\SurveyAnswerCell');
    }

    public static function make(Survey $survey, $order, $text, $type, $isRequired, $options)
    {
        $question = new SurveyQuestion();
        $revision_question = new \Lara\Library\Revision($question);
        $question->survey_id = $survey->id;
        $question->order = $order;
        $question->field_type = $type;
        $question->is_required = (bool)$isRequired;
        $question->question = $text;
        $question->save();
        $revision_question->save($question);

        //check if question is dropdown question
        if ($type === 3) {
            foreach ($options as $answerText) {
                SurveyAnswerOption::make($question, $answerText);
            }
        }

        return $question;
    }
}
