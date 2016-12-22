<?php


trait CreatesSurveys
{
    protected $survey;
    protected $questions;
    /**
     * @param array $params
     * @param bool $addRandomQuestion if true, will create a random question for the survey
     * @return SurveyViewTest
     */
    protected function makeSurvey($params = ['is_private' => false], $addRandomQuestion = true)
    {
        $this->survey = factory(Lara\Survey::class)->create($params);
        return $addRandomQuestion ? $this->addQuestion() : $this;
    }

    protected function addQuestion($params = [ 'order' => 0,])
    {
        $params['survey_id'] = $this->survey->id;
        //create question, without it displaying a view fails (because evaluation is not set)
        $question = factory(Lara\SurveyQuestion::class)->create($params);
        if ($question->field_type === 3) {
            factory(Lara\SurveyAnswerOption::class)->create(['survey_question_id' => $question->id]);
            factory(Lara\SurveyAnswerOption::class)->create(['survey_question_id' => $question->id]);
        }
        $this->questions[] = $question;
        return $this;
    }

    protected function visitSurvey()
    {
        return $this->visit('/survey/' . $this->survey->id);
    }
}