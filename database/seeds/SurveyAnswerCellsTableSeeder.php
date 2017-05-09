<?php

use Illuminate\Database\Seeder;

class SurveyAnswerCellsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('survey_answer_cells')->delete();

        factory(Lara\SurveyAnswerCell::class, 500)->create();
    }
}
