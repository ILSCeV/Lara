<?php

class SurveyCreationTest extends LaraTestCase
{
    protected $questionCount = 0;

    /** @before */
    function setQuestionsCountToZero()
    {
        $this->questionCount = 0;
    }

    /** @test */
    function the_creation_dropdown_can_be_activated_by_a_logged_in_user()
    {
        $user = factory(\Lara\Person::class)->create();
        $this->actingAsLara($user)
            ->visit('/')
            ->see('+')
            ->click('+')
            ->see(trans('mainLang.createAndAddNewSurvey'));
    }

    /** @test */
    function the_creation_dropdown_cant_be_activated_by_a_guest()
    {
        $this->visit('/')
            ->dontSee('+');
    }

    /** @test */
    function the_creation_dropdown_navigates_to_a_creation_dialog()
    {
        $user = factory(\Lara\Person::class)->create();
        $this->actingAsLara($user)
            ->visit('/')
            ->click('+')
            ->click(trans('mainLang.createAndAddNewSurvey'))
            ->seePageIs('/survey/create');
    }

    /** @test */
    function it_is_not_allowed_to_navigate_to_the_creation_dialog_as_guest()
    {
        $this->visit('/survey/create')
            ->seePageIsNot('/survey/create');
    }

    /** @test */
    function it_requires_a_title_for_creation()
    {
        $user = factory(\Lara\Person::class)->create();
        $this->actingAsLara($user)
            ->visit('/survey/create')
            ->type('', 'title')
            ->clickCreate()
            ->seePageIs('survey/create');
    }

    /** @test */
    function it_will_not_submit_if_title_description_and_no_question_is_filled()
    {
        $user = factory(\Lara\Person::class)->create();
        $this->actingAsLara($user)
            ->visit('survey/create')
            ->title('')
            ->descripton()
            ->clickCreate()
            ->seePageIs('survey/create');
    }

    /** @test */
    function it_will_submit_if_title_description_and_a_question_is_filled()
    {
        $user = factory(\Lara\Person::class)->create();
        $this->actingAsLara($user)
            ->visit('survey/create')
            ->type('My title', 'title')
            ->type('A lengthy description', 'description')
            ->storeInput('questionText[0]', 'My question', true)
            ->clickCreate()
            ->seePageIsNot('survey/create');
    }

    /** @test */
    function it_can_create_a_survey_with_a_freetext_question()
    {
        $user = factory(\Lara\Person::class)->create();
        $this->actingAsLara($user)
            ->visit('survey/create')
            ->type('My title', 'title')
            ->type('A lengthy description', 'description')
            ->storeInput('questionText[0]', 'My freetext question', true)
            ->press('button-create-survey')
            ->seeInDatabase('surveys', ['title' => 'My title'])
            ->seeInDatabase('survey_questions', ['question' => 'My freetext question', 'field_type' => 1]);
    }

    /** @test */
    function it_can_create_a_survey_with_a_choice_question()
    {
        $user = factory(\Lara\Person::class)->create();
        $this->actingAsLara($user)
            ->visit('survey/create')
            ->type('My title', 'title')
            ->type('A lengthy description', 'description')
            ->storeInput('questionText[0]', 'My choice question', true)
            ->select(2, 'type_select[0]')
            ->press('button-create-survey')
            ->seeInDatabase('surveys', ['title' => 'My title'])
            ->seeInDatabase('survey_questions', ['question' => 'My choice question', 'field_type' => 2]);
    }

    /** @test */
    function it_can_create_a_survey_with_a_custom_answer()
    {
        $this->gotoSurveyCreation()
            ->titleAndDescription()
            ->addQuestion('My custom question', 3)
            ->addAnswerOption('My custom answer option')
            ->clickCreate()
            ->seeInDatabase('survey_questions', ['question' => 'My custom question', 'field_type' => 3])
            ->seeInDatabase('survey_answer_options', ['answer_option' => 'My custom answer option']);
    }

    /** @test */
    function it_can_mark_a_question_as_required()
    {
        $this->gotoSurveyCreation()
            ->titleAndDescription()
            ->addQuestion('My first question')
            ->check('required[0]')
            ->clickCreate()
            ->seeInDatabase('survey_questions', ['question' => 'My first question', 'field_type' => 1, 'is_required' => 1]);
    }

    // TODO: Move to selenium tests! Can't test two questions without Javascript
    function it_can_create_a_survey_with_two_questions()
    {
        $this->gotoSurveyCreation()
            ->titleAndDescription()
            ->addQuestion('My first question')
            ->addQuestion('My second question', 2)
            ->clickCreate()
            ->seeInDatabase('survey_questions', ['question' => 'My first question'])
            ->seeInDatabase('survey_questions', ['question' => 'My second question', 'field_type' => 2]);
    }

    private function titleAndDescription($title = 'My title', $description = 'A lengthy description')
    {
        return $this->title($title)->descripton($description);
    }
    /**
     * @param string $title
     * @return $this
     */
    private function title($title = 'My title')
    {
        return $this->type($title, 'title');
    }

    /**
     * @param string $description
     * @return $this
     */
    private function descripton($description = 'A lengthy description')
    {
        return $this->type($description, 'description');
    }

    /**
     * @return $this
     */
    private function addQuestion($text, $type = 1)
    {
        $count = $this->questionCount++;
        return $this
            ->storeInput('questionText[' . $count . ']', $text)
            ->select($type, 'type_select[' . $count . ']') ;
    }

    /**
     * @return $this
     */
    private function clickCreate()
    {
        return $this->press('button-create-survey');
    }

    /**
     * @return $this
     */
    private function addAnswerOption($text, $questionIndex = 0, $answerIndex = 0)
    {
        return $this ->storeInput('answerOption['. $questionIndex .']['. $answerIndex.']', $text, true);
    }

    private function gotoSurveyCreation()
    {
        $user = factory(\Lara\Person::class)->create();
        return $this->actingAsLara($user)
            ->visit('survey/create');
    }

}