<?php

use Illuminate\Database\Seeder;

class SchedulesTableSeeder extends Seeder
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
        DB::table('schedules')->delete();

		/**
		 * Creating event schedules
		 */
        \Lara\Schedule::create(array('schdl_title' => 'Example event template', 
									 'schdl_time_preparation_start' => '20:00',
									 'schdl_password' => Hash::make('password'),
									 'evnt_id' => '1',
									 'entry_revisions' => NULL,
									 'schdl_is_template' => '1'));
							
		/**
		 * Reporting result to console
		 */
		$this->command->info('One event schedule for event ID 1 and one example task created on 01.11.2015.');
    }

}