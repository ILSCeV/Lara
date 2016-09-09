<?php

class SurveyCreationTest extends LaraTestCase
{
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
            ->press('button-create-survey')
            ->seePageIs('survey/create');
    }

    /** @test */
    function it_will_not_submit_if_title_description_and_no_question_is_filled()
    {
        $user = factory(\Lara\Person::class)->create();
        $this->actingAsLara($user)
            ->visit('/survey/create')
            ->type('My title', 'title')
            ->type('A lengthy description', 'description')
            ->press('button-create-survey')
            ->seePageIs('survey/create');
    }

    function it_will_submit_if_title_description_and_a_question_is_filled()
    {
        $user = factory(\Lara\Person::class)->create();
        $this->actingAsLara($user)
            ->visit('/survey/create')
            ->type('My title', 'title')
            ->type('A lengthy description', 'description')
            ->see(trans('mainLang.question'))
            ->storeInput('questions[0]' , 'My question' ,true)
            ->press('button-create-survey')
            ->seePageIsNot('survey/create');
    }




}