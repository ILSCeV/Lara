<?php

class SurveyViewTest extends LaraTestCase {

    /** @test */
    function it_can_visit_a_public_surey_as_guest()
    {
        $survey = factory(Lara\Survey::class)->create(['is_private' => false]);
        //create question, without it displaying a view fails (because evaluation is not set)
        $question = factory(Lara\SurveyQuestion::class)->create(['survey_id' => $survey->id, 'order' => 0]);

        $this->visit('/survey/' . $survey->id)
            ->seePageIs('/survey/' . $survey->id);
    }

    /** @test */
    function it_cant_visit_a_private_survey_as_guest()
    {
        $survey = factory(Lara\Survey::class)->create(['is_private' => true]);
        //create question, without it displaying a view fails (because evaluation is not set)
        $question = factory(Lara\SurveyQuestion::class)->create(['survey_id' => $survey->id, 'order' => 0]);

        $this->visit('/survey/' . $survey->id)
            ->seePageIsNot('/survey/' . $survey->id);
    }

    /** @test */
    function it_can_visit_a_private_survey_as_member()
    {
        $user = factory(Lara\Person::class)->create();
        $survey = factory(Lara\Survey::class)->create(['is_private' => true]);
        //create question, without it displaying a view fails (because evaluation is not set)
        $question = factory(Lara\SurveyQuestion::class)->create(['survey_id' => $survey->id, 'order' => 0]);

        $this->actingAsLara($user)
            ->visit('/survey/' . $survey->id)
            ->seePageIs('/survey/' . $survey->id);
    }
}
