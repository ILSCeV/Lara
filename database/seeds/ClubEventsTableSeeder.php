<?php

use Illuminate\Database\Seeder;

class ClubEventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return console info
     */
    public function run()
    {
		/**
		 * Clearing table
		 */
        DB::table('club_events')->delete();

		/**
		 * Creating public events
		 */
        \Lara\ClubEvent::create(array('evnt_title' => 'Example Event', 
									  'evnt_subtitle' => 'Event Subtitle',
									  'plc_id' => '1',
									  'evnt_date_start' => '2015-11-01',
									  'evnt_date_end' => '2015-11-02',
									  'evnt_time_start' => '21:00',
									  'evnt_time_end' => '01:00',
									  'evnt_public_info' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
									  'evnt_private_details' => 'Use "password" for password.',
									  'evnt_is_private' => '0'));
	
		/**
		 * Reporting result to console
		 */
		$this->command->info('Example event created on 01.11.2015.');
    }
}
