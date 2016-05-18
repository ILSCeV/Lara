<?php

use Illuminate\Database\Seeder;

class ScheduleEntriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('schedule_entries')->delete();
        
        \DB::table('schedule_entries')->insert(array (
            0 => 
            array (
                'id' => 1,
                'schdl_id' => 1,
                'jbtyp_id' => 1,
                'prsn_id' => 1,
                'entry_user_comment' => '',
                'entry_time_start' => '00:00:00',
                'entry_time_end' => '00:00:00',
                'entry_statistical_weight' => 0.0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            1 => 
            array (
                'id' => 2,
                'schdl_id' => 1,
                'jbtyp_id' => 2,
                'prsn_id' => 2,
                'entry_user_comment' => '',
                'entry_time_start' => '00:00:00',
                'entry_time_end' => '00:00:00',
                'entry_statistical_weight' => 0.0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            2 => 
            array (
                'id' => 3,
                'schdl_id' => 1,
                'jbtyp_id' => 3,
                'prsn_id' => NULL,
                'entry_user_comment' => '',
                'entry_time_start' => '00:00:00',
                'entry_time_end' => '00:00:00',
                'entry_statistical_weight' => 0.0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            3 => 
            array (
                'id' => 4,
                'schdl_id' => 1,
                'jbtyp_id' => 4,
                'prsn_id' => 4,
                'entry_user_comment' => 'Thou didst not reckon with the might of Thor, knave!',
                'entry_time_start' => '00:00:00',
                'entry_time_end' => '00:00:00',
                'entry_statistical_weight' => 0.0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            4 => 
            array (
                'id' => 5,
                'schdl_id' => 1,
                'jbtyp_id' => 5,
                'prsn_id' => 3,
                'entry_user_comment' => 'komme 10 Min später',
                'entry_time_start' => '00:00:00',
                'entry_time_end' => '00:00:00',
                'entry_statistical_weight' => 0.0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            5 => 
            array (
                'id' => 6,
                'schdl_id' => 1,
                'jbtyp_id' => 1,
                'prsn_id' => NULL,
                'entry_user_comment' => '',
                'entry_time_start' => '00:00:00',
                'entry_time_end' => '00:00:00',
                'entry_statistical_weight' => 0.0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            6 => 
            array (
                'id' => 7,
                'schdl_id' => 2,
                'jbtyp_id' => 6,
                'prsn_id' => 3,
                'entry_user_comment' => '',
                'entry_time_start' => '00:00:00',
                'entry_time_end' => '00:00:00',
                'entry_statistical_weight' => 0.0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            7 => 
            array (
                'id' => 8,
                'schdl_id' => 2,
                'jbtyp_id' => 7,
                'prsn_id' => NULL,
                'entry_user_comment' => '',
                'entry_time_start' => '00:00:00',
                'entry_time_end' => '00:00:00',
                'entry_statistical_weight' => 0.0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
        ));
        
        
    }
}
