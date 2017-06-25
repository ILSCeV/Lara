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

        factory(Lara\SurveyAnswer::class, 100)->create();
    }
}
