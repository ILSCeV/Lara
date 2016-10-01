<?php

use Laracasts\Integrated\Extensions\Selenium;
use Laracasts\Integrated\Services\Laravel\Application as Laravel;

class SurveyViewTest extends Selenium
{
    protected $baseUrl = 'http://localhost';
    use Laravel;
    use CreatesSurveys;
    use SeleniumLogin;

    /** @test */
    function it_can_visit_the_homepage()
    {
        $this->visit('/');
    }

    /** @test */
    function it_can_log_in()
    {
        $this->logIn();
    }

    /** @test */
    function cant_answer_a_public_survey_if_there_is_an_unanswered_required_question()
    {
        $this->makeSurvey(['is_private' => false], false)
            ->addQuestion(['order' => 0, 'is_required' => 1, 'field_type' => 1])
            ->visitSurvey()
            ->type('My Name', 'name')
            ->type('My Club', 'club')
            ->submitForm('');
    }


    /** @test */
    function it_can_access_a_public_survey()
    {
        $this->makeSurvey()
            ->visitSurvey();
    }
}