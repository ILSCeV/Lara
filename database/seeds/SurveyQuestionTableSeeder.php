<?php

use Illuminate\Database\Seeder;

class SurveyQuestionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('survey_question')->delete();
        
        \DB::table('survey_question')->insert(array (
            0 => 
            array (
                'id' => 1,
                'survey_id' => 1,
                'number' => 0,
                'fieldType' => 1,
                'content' => 'Kommst du zur Weihnachtsfeier?',
                'created_at' => '2016-05-18 18:58:37',
                'updated_at' => '2016-05-18 18:58:37',
            ),
            1 => 
            array (
                'id' => 2,
                'survey_id' => 1,
                'number' => 1,
                'fieldType' => 1,
                'content' => '',
                'created_at' => '2016-05-18 18:58:37',
                'updated_at' => '2016-05-18 18:58:37',
            ),
        ));
        
        
    }
}
