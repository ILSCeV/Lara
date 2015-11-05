<?php

use Illuminate\Database\Seeder;

class ScheduleEntriesTableSeeder extends Seeder
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
        DB::table('schedule_entries')->delete();

		/**
		 * Creating event schedule entries
		 */
        \Lara\ScheduleEntry::create(array('schdl_id' => '1', 
										  'jbtyp_id' => '1',
										  'prsn_id' => '1'));	
		 
        \Lara\ScheduleEntry::create(array('schdl_id' => '1', 
										  'jbtyp_id' => '2',
										  'prsn_id' => '2'));

		\Lara\ScheduleEntry::create(array('schdl_id' => '1', 
										  'jbtyp_id' => '3'));
							
		\Lara\ScheduleEntry::create(array('schdl_id' => '1', 
										  'jbtyp_id' => '4',
										  'entry_user_comment' => 'Thou didst not reckon with the might of Thor, knave!',
										  'prsn_id' => '4'));
							
		\Lara\ScheduleEntry::create(array('schdl_id' => '1', 
										  'jbtyp_id' => '5',
										  'entry_user_comment' => 'komme 10 Min später',
										  'prsn_id' => '3'));
							
		\Lara\ScheduleEntry::create(array('schdl_id' => '1', 
										  'jbtyp_id' => '1'));
												
						
		/**
		 * Creating task schedule entries
		 */					
        \Lara\ScheduleEntry::create(array('schdl_id' => '2', 
										  'jbtyp_id' => '6',
										  'prsn_id' => '3'));
							
        \Lara\ScheduleEntry::create(array('schdl_id' => '2', 
										  'jbtyp_id' => '7'));
							
		/**
		 * Reporting result to console
		 */
		$this->command->info('Added schedule entries to example event and task on 01.11.2015.');
    }
}
