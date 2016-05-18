<?php

use Illuminate\Database\Seeder;

class ClubEventsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('club_events')->delete();
        
        \DB::table('club_events')->insert(array (
            0 => 
            array (
                'id' => 1,
                'evnt_type' => 0,
                'evnt_title' => 'Example Event',
                'evnt_subtitle' => 'Event Subtitle',
                'plc_id' => 1,
                'evnt_show_to_club' => '["-"]',
                'evnt_date_start' => '2016-06-01',
                'evnt_date_end' => '2016-06-02',
                'evnt_time_start' => '21:00:00',
                'evnt_time_end' => '01:00:00',
                'evnt_public_info' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
                'evnt_private_details' => 'Use "password" for password.',
                'evnt_is_private' => 0,
                'created_at' => '2016-01-01 13:00:00',
                'updated_at' => '2016-01-01 13:00:00',
            ),
        ));
        
        
    }
}
