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
        

        \DB::table('survey_questions')->delete();
        
        \DB::table('survey_questions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'survey_id' => 1,
                'order' => 0,
                'field_type' => 1,
                'question' => 'Wo wollt ihr hin?',
                'created_at' => '2016-05-25 23:19:07',
                'updated_at' => '2016-05-25 23:19:07',
            ),
            1 => 
            array (
                'id' => 2,
                'survey_id' => 1,
                'order' => 1,
                'field_type' => 1,
                'question' => 'Wer kann ein Zelt mitbringen?',
                'created_at' => '2016-05-25 23:19:07',
                'updated_at' => '2016-05-25 23:19:07',
            ),
            2 => 
            array (
                'id' => 3,
                'survey_id' => 2,
                'order' => 0,
                'field_type' => 1,
                'question' => 'Möchtest du ein Shirt haben?',
                'created_at' => '2016-05-26 15:09:21',
                'updated_at' => '2016-05-26 15:09:21',
            ),
            3 => 
            array (
                'id' => 4,
                'survey_id' => 2,
                'order' => 1,
                'field_type' => 1,
                'question' => 'Welche Größe brauchst du?',
                'created_at' => '2016-05-26 15:09:21',
                'updated_at' => '2016-05-26 15:09:21',
            ),
        ));
        
        
    }
}
