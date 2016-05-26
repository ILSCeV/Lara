<?php

use Illuminate\Database\Seeder;

class SurveysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('surveys')->delete();
        
        \DB::table('surveys')->insert(array (
            0 => 
            array (
                'id' => 1,
                'prsn_id' => 1003,
                'title' => 'Clubfahrt',
                'description' => '',
                'deadline' => '2016-05-25 00:00:00',
                'in_calendar' => '2016-05-25',
                'is_private' => 0,
                'is_anonymous' => 0,
                'show_results_after_voting' => 0,
                'created_at' => '2016-05-25 23:19:07',
                'updated_at' => '2016-05-25 23:19:07',
            ),
        ));
        
        
    }
}
