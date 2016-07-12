<?php

use Illuminate\Database\Seeder;

class SurveyAnswerOptionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('survey_answer_options')->delete();
        
        \DB::table('survey_answer_options')->insert(array (
            0 => 
            array (
                'id' => 1,
                'survey_question_id' => 2,
                'answer_option' => 'XS',
                'deleted_at' => NULL,
                'created_at' => '2016-07-12 13:07:41',
                'updated_at' => '2016-07-12 13:07:41',
            ),
            1 => 
            array (
                'id' => 2,
                'survey_question_id' => 2,
                'answer_option' => 'S',
                'deleted_at' => NULL,
                'created_at' => '2016-07-12 13:07:41',
                'updated_at' => '2016-07-12 13:07:41',
            ),
            2 => 
            array (
                'id' => 3,
                'survey_question_id' => 2,
                'answer_option' => 'M',
                'deleted_at' => NULL,
                'created_at' => '2016-07-12 13:07:41',
                'updated_at' => '2016-07-12 13:07:41',
            ),
            3 => 
            array (
                'id' => 4,
                'survey_question_id' => 2,
                'answer_option' => 'L',
                'deleted_at' => NULL,
                'created_at' => '2016-07-12 13:07:41',
                'updated_at' => '2016-07-12 13:07:41',
            ),
            4 => 
            array (
                'id' => 5,
                'survey_question_id' => 2,
                'answer_option' => 'XL',
                'deleted_at' => NULL,
                'created_at' => '2016-07-12 13:07:41',
                'updated_at' => '2016-07-12 13:07:41',
            ),
            5 => 
            array (
                'id' => 6,
                'survey_question_id' => 8,
                'answer_option' => 'Antwort 1',
                'deleted_at' => NULL,
                'created_at' => '2016-07-12 15:29:33',
                'updated_at' => '2016-07-12 15:29:33',
            ),
            6 => 
            array (
                'id' => 7,
                'survey_question_id' => 8,
                'answer_option' => 'Antwort 2',
                'deleted_at' => NULL,
                'created_at' => '2016-07-12 15:29:33',
                'updated_at' => '2016-07-12 15:29:33',
            ),
            7 => 
            array (
                'id' => 8,
                'survey_question_id' => 13,
                'answer_option' => 'S',
                'deleted_at' => NULL,
                'created_at' => '2016-07-12 15:34:16',
                'updated_at' => '2016-07-12 15:34:16',
            ),
            8 => 
            array (
                'id' => 9,
                'survey_question_id' => 13,
                'answer_option' => 'M',
                'deleted_at' => NULL,
                'created_at' => '2016-07-12 15:34:16',
                'updated_at' => '2016-07-12 15:34:16',
            ),
            9 => 
            array (
                'id' => 10,
                'survey_question_id' => 13,
                'answer_option' => 'L',
                'deleted_at' => NULL,
                'created_at' => '2016-07-12 15:34:16',
                'updated_at' => '2016-07-12 15:34:16',
            ),
        ));
        
        
    }
}
