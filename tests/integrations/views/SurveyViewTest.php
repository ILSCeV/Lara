<?php

class SurveyViewTest extends LaraTestCase {

    use CreatesSurveys;

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
}
