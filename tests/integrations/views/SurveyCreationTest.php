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

}