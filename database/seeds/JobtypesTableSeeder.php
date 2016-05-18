<?php

use Illuminate\Database\Seeder;

class JobtypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('jobtypes')->delete();
        
        \DB::table('jobtypes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'jbtyp_title' => 'Einlass',
                'jbtyp_time_start' => '00:00:00',
                'jbtyp_time_end' => '00:00:00',
                'jbtyp_needs_preparation' => 0,
                'jbtyp_statistical_weight' => 1.0,
                'jbtyp_is_archived' => 0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            1 => 
            array (
                'id' => 2,
                'jbtyp_title' => 'Bar',
                'jbtyp_time_start' => '00:00:00',
                'jbtyp_time_end' => '00:00:00',
                'jbtyp_needs_preparation' => 0,
                'jbtyp_statistical_weight' => 2.0,
                'jbtyp_is_archived' => 0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            2 => 
            array (
                'id' => 3,
                'jbtyp_title' => 'Tresen',
                'jbtyp_time_start' => '00:00:00',
                'jbtyp_time_end' => '00:00:00',
                'jbtyp_needs_preparation' => 0,
                'jbtyp_statistical_weight' => 2.0,
                'jbtyp_is_archived' => 0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            3 => 
            array (
                'id' => 4,
                'jbtyp_title' => 'Disko',
                'jbtyp_time_start' => '00:00:00',
                'jbtyp_time_end' => '00:00:00',
                'jbtyp_needs_preparation' => 0,
                'jbtyp_statistical_weight' => 2.0,
                'jbtyp_is_archived' => 0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            4 => 
            array (
                'id' => 5,
                'jbtyp_title' => 'Licht',
                'jbtyp_time_start' => '00:00:00',
                'jbtyp_time_end' => '00:00:00',
                'jbtyp_needs_preparation' => 0,
                'jbtyp_statistical_weight' => 1.0,
                'jbtyp_is_archived' => 0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            5 => 
            array (
                'id' => 6,
                'jbtyp_title' => 'Buy beer',
                'jbtyp_time_start' => '00:00:00',
                'jbtyp_time_end' => '00:00:00',
                'jbtyp_needs_preparation' => 0,
                'jbtyp_statistical_weight' => 10.0,
                'jbtyp_is_archived' => 0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            6 => 
            array (
                'id' => 7,
                'jbtyp_title' => 'Drink beer',
                'jbtyp_time_start' => '00:00:00',
                'jbtyp_time_end' => '00:00:00',
                'jbtyp_needs_preparation' => 0,
                'jbtyp_statistical_weight' => 30.0,
                'jbtyp_is_archived' => 0,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
        ));
        
        
    }
}
