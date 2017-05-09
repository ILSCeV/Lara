<?php

use Illuminate\Database\Seeder;

class SurveyAnswersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('survey_answers')->delete();

        factory(Lara\SurveyAnswer::class, 100)->create()
            ->each(function(Lara\SurveyAnswer $answer) {
                $survey = $answer->survey;
                $questions = $survey->questions;

                foreach ($questions as $question) {
                    $cell = factory(Lara\SurveyAnswerCell::class)->make();
                    $cell->survey_answer_id = $answer->id;
                    $cell->survey_question_id = $question->id;
                }
            });
    }
}
