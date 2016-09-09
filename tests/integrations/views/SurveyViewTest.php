<?php

class SurveyViewTest extends LaraTestCase {

    protected $survey;
    protected $questions;

    /** @test */
    function can_visit_a_public_survey_as_guest()
    {
        $this->makeSurvey()
            ->visitSurvey()
            ->seePageIs('/survey/' . $this->survey->id);
    }

    /** @test */
    function cant_visit_a_private_survey_as_guest()
    {
        $this->makeSurvey(['is_private' => true])
            ->visitSurvey()
            ->seePageIsNot('/survey/' . $this->survey->id);
    }

    /** @test */
    function can_visit_a_private_survey_as_member()
    {
        $user = factory(Lara\Person::class)->create();

        $this->actingAsLara($user)
            ->makeSurvey()
            ->visitSurvey()
            ->seePageIs('/survey/' . $this->survey->id);
    }

    /** @test */
    function can_answer_a_public_survey_as_guest()
    {
        $this->makeSurvey()
            ->visitSurvey()
            ->type('My Name', 'name')
            ->type('My Club', 'club')
            ->press('noMarginMobile')
            ->seeInDatabase('survey_answers', ['name' => 'My Name', 'club' => 'My Club']);
    }

    function cant_answer_a_public_survey_if_there_is_an_unanswered_required_question()
    {

        $this->makeSurvey(['is_private' => false], false)
            ->addQuestion(['order' => 0, 'is_required' => 1, 'field_type' => 1])
            ->visitSurvey()
            ->type('My Name', 'name')
            ->type('My Club', 'club')
            ->press('noMarginMobile')
            ->notSeeInDatabase('survey_answers', ['name' => 'My Name'])
            ->notSeeInDatabase('survey_answers', ['club' => 'My Club']);
    }

    /** @test */
    function cant_answer_a_passworded_survey_without_the_correct_password()
    {
        $this->makeSurvey(['password' => Hash::make("password")])
            ->visitSurvey()
            ->type('My Name', 'name')
            ->type('My Club', 'club')
            ->press('noMarginMobile')
            ->notSeeInDatabase('survey_answers', ['name' => 'My Name'])
            ->notSeeInDatabase('survey_answers', ['club' => 'My Club']);
    }

    /** @test */
    function can_answer_a_passworded_survey_with_the_correct_password()
    {
        $this->makeSurvey(['password' => Hash::make("password")])
            ->visitSurvey()
            ->type('My Name', 'name')
            ->type('My Club', 'club')
            ->type('password', 'password')
            ->press('noMarginMobile')
            ->seeInDatabase('survey_answers', ['name' => 'My Name'])
            ->seeInDatabase('survey_answers', ['club' => 'My Club']);
    }
    
    /** @test */
    function it_marks_required_questions_with_a_star()
    {
        $this->makeSurvey(['is_private' => false], false)
            ->addQuestion(['order' => 0, 'is_required' => 1, 'field_type' => 1])
            ->visitSurvey()
            ->see($this->questions[0]->question . ' *');
    }
    

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
        }
        $this->questions[] = $question;
        return $this;
    }
    
    protected function visitSurvey()
    {
        return $this->visit('/survey/' . $this->survey->id);
    }

}
