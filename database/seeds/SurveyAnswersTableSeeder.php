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
        

        \DB::table('survey_answers')->delete();
        
        \DB::table('survey_answers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'creator_id' => 1003,
                'survey_id' => 1,
                'name' => 'Peter',
                'club' => 'bc-Club',
                'order' => 0,
                'created_at' => '2016-05-25 23:19:14',
                'updated_at' => '2016-05-25 23:19:14',
            ),
            1 => 
            array (
                'id' => 2,
                'creator_id' => 1003,
                'survey_id' => 1,
                'name' => 'Franz',
                'club' => 'Verein der dreifach geschiedenen Männer',
                'order' => 0,
                'created_at' => '2016-05-25 23:19:18',
                'updated_at' => '2016-05-25 23:19:18',
            ),
            2 => 
            array (
                'id' => 3,
                'creator_id' => 1003,
                'survey_id' => 1,
                'name' => 'Maria',
                'club' => 'Solo',
                'order' => 0,
                'created_at' => '2016-05-25 23:19:22',
                'updated_at' => '2016-05-25 23:19:22',
            ),
            3 => 
            array (
                'id' => 4,
                'creator_id' => 1003,
                'survey_id' => 1,
                'name' => 'Thomas',
                'club' => 'Club der notorischen Säufer',
                'order' => 0,
                'created_at' => '2016-05-25 23:59:08',
                'updated_at' => '2016-05-25 23:59:08',
            ),
            4 => 
            array (
                'id' => 5,
                'creator_id' => 1007,
                'survey_id' => 2,
                'name' => 'Peter',
                'club' => 'Club 2',
                'order' => 0,
                'created_at' => '2016-05-26 15:14:07',
                'updated_at' => '2016-05-26 15:14:07',
            ),
        ));
        
        
    }
}
