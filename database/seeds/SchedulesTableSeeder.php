<?php

use Illuminate\Database\Seeder;

class SchedulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('schedules')->delete();
        
        \DB::table('schedules')->insert(array (
            0 => 
            array (
                'id' => 1,
                'schdl_title' => 'Example event template',
                'schdl_time_preparation_start' => '20:00:00',
                'schdl_password' => '$2y$10$PJYIPNf/4Zt91PjlM82oKuSKRkYHgXOvvHKjql0D/JWzgzV2uLOOK',
                'evnt_id' => '1',
                'entry_revisions' => NULL,
                'schdl_is_template' => 1,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            1 => 
            array (
                'id' => 2,
                'schdl_title' => 'Example task',
                'schdl_time_preparation_start' => NULL,
                'schdl_password' => '',
                'evnt_id' => NULL,
                'entry_revisions' => NULL,
                'schdl_is_template' => 0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
        ));
        
        
    }
}
