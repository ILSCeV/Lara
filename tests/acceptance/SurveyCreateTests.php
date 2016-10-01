
<?php

use Laracasts\Integrated\Extensions\Selenium;
use Laracasts\Integrated\Services\Laravel\Application as Laravel;

class SurveyViewTest extends Selenium
{
    protected $baseUrl = 'http://localhost';

    use Laravel;
    use SeleniumLogin;

    /** @test */
    function it_can_visit_the_survey_creation_dialog()
    {
        $this->logIn()
            ->goToCreationDialog()
            ->seePageIs('survey/create');
    }

    /** @test */
    function it_can_add_a_question_by_pressing_the_add_button()
    {
        $this->logIn()
            ->goToCreationDialog()
            ->clickButton(trans('mainLang.addQuestion'))
            ->waitForElement('questionText\[1\]');
    }

    /** @test */
    function it_updates_the_indices_of_questions_after_the_first_one_was_deleted()
    {
        $this->logIn()
            ->goToCreationDialog()
            ->clickButton(trans('mainLang.addQuestion'))
            ->waitForElement('questionText\[1\]')
            ->clickButton('')
            ->see('questionText[0]')
            ->notSee('questionText[1]');
    }

    /** @test */
    function it_can_change_the_type_select()
    {

    }

    private function goToCreationDialog()
    {
        return $this->click('+')
            ->click(trans('mainLang.createAndAddNewSurvey'));
    }

    /**
     * We need this method for buttons that we just want to click, but not submit
     * @param $buttonText
     * @return static
     */
    private function clickButton($buttonText)
    {
        $this->findByValue($buttonText)->click();
        return $this->updateCurrentUrl();
    }

}
