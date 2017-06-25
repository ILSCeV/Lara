<?php

use Illuminate\Database\Seeder;

class SurveyQuestionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('survey_questions')->delete();
        factory(Lara\SurveyQuestion::class, 50)->create();
    }
}
