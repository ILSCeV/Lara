<?php

use Illuminate\Database\Seeder;

class SurveyTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('survey')->delete();
        
        \DB::table('survey')->insert(array (
            0 => 
            array (
                'id' => 1,
                'prsn_id' => 1007,
                'title' => 'Testumfrage',
                'description' => 'Bitte ausfüllen.',
                'deadline' => '1970-01-01 01:00:00',
                'created_at' => '2016-05-18 18:58:37',
                'updated_at' => '2016-05-18 18:58:37',
            ),
        ));
        
        
    }
}
