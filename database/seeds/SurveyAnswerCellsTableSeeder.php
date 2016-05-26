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
        
        \DB::table('survey_answer_cells')->insert(array (
            0 => 
            array (
                'id' => 1,
                'survey_answer_id' => 1,
                'survey_question_id' => 1,
                'answer' => 'Ostsee',
                'created_at' => '2016-05-25 23:19:14',
                'updated_at' => '2016-05-25 23:19:14',
            ),
            1 => 
            array (
                'id' => 2,
                'survey_answer_id' => 1,
                'survey_question_id' => 2,
                'answer' => 'Nein',
                'created_at' => '2016-05-25 23:19:14',
                'updated_at' => '2016-05-25 23:19:14',
            ),
            2 => 
            array (
                'id' => 3,
                'survey_answer_id' => 2,
                'survey_question_id' => 1,
                'answer' => 'Mittelmeer',
                'created_at' => '2016-05-25 23:19:18',
                'updated_at' => '2016-05-25 23:19:18',
            ),
            3 => 
            array (
                'id' => 4,
                'survey_answer_id' => 2,
                'survey_question_id' => 2,
                'answer' => 'Ja',
                'created_at' => '2016-05-25 23:19:18',
                'updated_at' => '2016-05-25 23:19:18',
            ),
            4 => 
            array (
                'id' => 5,
                'survey_answer_id' => 3,
                'survey_question_id' => 1,
                'answer' => 'Brandenburg',
                'created_at' => '2016-05-25 23:19:22',
                'updated_at' => '2016-05-25 23:19:22',
            ),
            5 => 
            array (
                'id' => 6,
                'survey_answer_id' => 3,
                'survey_question_id' => 2,
                'answer' => 'Ja',
                'created_at' => '2016-05-25 23:19:22',
                'updated_at' => '2016-05-25 23:19:22',
            ),
            6 => 
            array (
                'id' => 7,
                'survey_answer_id' => 4,
                'survey_question_id' => 1,
                'answer' => 'Niederlande',
                'created_at' => '2016-05-25 23:59:08',
                'updated_at' => '2016-05-25 23:59:08',
            ),
            7 => 
            array (
                'id' => 8,
                'survey_answer_id' => 4,
                'survey_question_id' => 2,
                'answer' => 'Ja',
                'created_at' => '2016-05-25 23:59:08',
                'updated_at' => '2016-05-25 23:59:08',
            ),
            8 => 
            array (
                'id' => 9,
                'survey_answer_id' => 5,
                'survey_question_id' => 3,
                'answer' => 'Ja',
                'created_at' => '2016-05-26 15:14:07',
                'updated_at' => '2016-05-26 15:14:07',
            ),
            9 => 
            array (
                'id' => 10,
                'survey_answer_id' => 5,
                'survey_question_id' => 4,
                'answer' => 'L',
                'created_at' => '2016-05-26 15:14:07',
                'updated_at' => '2016-05-26 15:14:07',
            ),
        ));
        
        
    }
}
