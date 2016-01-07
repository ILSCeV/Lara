<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 * Each table gets its own method.
	 * Tables will be cleared and filled with example entries.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('ClubEventsTableSeeder');
		$this->call('SchedulesTableSeeder');
		$this->call('PersonsTableSeeder');
		$this->call('JobtypesTableSeeder');
		$this->call('PlacesTableSeeder');
		$this->call('ClubsTableSeeder');
		$this->call('ScheduleEntriesTableSeeder');
	}
}

class ClubEventsTableSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();
		DB::table('club_events')->delete();
		ClubEvent::create(array(
			'evnt_title' => 'Example Event',
			'evnt_subtitle' => 'Event Subtitle',
			'plc_id' => '1',
			'evnt_date_start' => '2016-01-01',
			'evnt_date_end' => '2016-01-02',
			'evnt_time_start' => '21:00',
			'evnt_time_end' => '01:00',
			'evnt_public_info' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
			'evnt_private_details' => 'Use "password" for password.',
			'evnt_is_private' => '0'
		));
		$this->command->info('Example event created on 2016-01-01.');
	}

}

class SchedulesTableSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();
		DB::table('schedules')->delete();
		Schedule::create(array(
			'schdl_title' => 'Example event template',
			'schdl_time_preparation_start' => '20:00',
			'schdl_password' => Hash::make('password'),
			'evnt_id' => '1',
			'schdl_is_template' => '1'
		));
		Schedule::create(array(
			'schdl_title' => 'Example task',
			'schdl_due_date' => '2016-01-01',
			'schdl_is_template' => '0'
		));
		$this->command->info('One event schedule for event ID 1 and one example task created on 2016-01-01.');
	}

}

class PersonsTableSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();
		DB::table('persons')->delete();
		Person::create(array(
			'prsn_name' => 'Max',
			'prsn_ldap_id' => '1111',
			'prsn_status' => 'kandidat',
			'clb_id' => '1'
		));
		Person::create(array(
			'prsn_name' => 'Otto',
			'prsn_ldap_id' => '1222',
			'prsn_status' => 'aktiv',
			'clb_id' => '1'
		));
		Person::create(array(
			'prsn_name' => 'Lena',
			'prsn_ldap_id' => '1333',
			'prsn_status' => 'veteran',
			'clb_id' => '1'
		));
		Person::create(array(
			'prsn_name' => 'THOR'
		));
		$this->command->info('Four example persons created - members Max, Otto, Lena and a guest THOR.');
	}

}

class JobtypesTableSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();
		DB::table('jobtypes')->delete();
		Jobtype::create(array(
			'jbtyp_title' => 'Einlass',
			'jbtyp_statistical_weight' => '1',
			'jbtyp_is_archived' => '0'
		));
		Jobtype::create(array(
			'jbtyp_title' => 'Bar',
			'jbtyp_statistical_weight' => '2',
			'jbtyp_is_archived' => '0'
		));
		Jobtype::create(array(
			'jbtyp_title' => 'Tresen',
			'jbtyp_statistical_weight' => '2',
			'jbtyp_is_archived' => '0'
		));
		Jobtype::create(array(
			'jbtyp_title' => 'Disko',
			'jbtyp_statistical_weight' => '2',
			'jbtyp_is_archived' => '0'
		));
		Jobtype::create(array(
			'jbtyp_title' => 'Licht',
			'jbtyp_statistical_weight' => '1',
			'jbtyp_is_archived' => '0'
		));
		Jobtype::create(array(
			'jbtyp_title' => 'Buy beer',
			'jbtyp_statistical_weight' => '10',
			'jbtyp_is_archived' => '0'
		));
		Jobtype::create(array(
			'jbtyp_title' => 'Drink beer',
			'jbtyp_statistical_weight' => '30',
			'jbtyp_is_archived' => '0'
		));
		$this->command->info('Example job types created.');
	}

}

class PlacesTableSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();
		DB::table('places')->delete();
		Place::create(array('plc_title' => '-')); // default - do not delete!
		$this->command->info('Default location created.');
	}

}

class ClubsTableSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();
		DB::table('clubs')->delete();
		Club::create(array('clb_title' => '-')); // default - do not delete!
		$this->command->info('Default club created.');
	}

}

class ScheduleEntriesTableSeeder extends Seeder {

	public function run()
	{
		Eloquent::unguard();
		DB::table('schedule_entries')->delete();

		/**
		 * Creating event schedule entries
		 */
		ScheduleEntry::create(array(
			'schdl_id' => '1',
			'jbtyp_id' => '1',
			'prsn_id' => '1'
		));
		ScheduleEntry::create(array(
			'schdl_id' => '1',
			'jbtyp_id' => '2',
			'prsn_id' => '2'
		));
		ScheduleEntry::create(array(
			'schdl_id' => '1',
			'jbtyp_id' => '3'
		));
		ScheduleEntry::create(array(
			'schdl_id' => '1',
			'jbtyp_id' => '4',
			'entry_user_comment' => 'Thou didst not reckon with the might of Thor, knave!',
			'prsn_id' => '4'
		));
		ScheduleEntry::create(array(
			'schdl_id' => '1',
			'jbtyp_id' => '5',
			'entry_user_comment' => 'komme 10 Min später',
			'prsn_id' => '3'
		));
		ScheduleEntry::create(array(
			'schdl_id' => '1',
			'jbtyp_id' => '1'
		));

		/**
		 * Creating task schedule
		 */
		ScheduleEntry::create(array(
			'schdl_id' => '2',
			'jbtyp_id' => '6',
			'prsn_id' => '3'
		));
		ScheduleEntry::create(array(
			'schdl_id' => '2',
			'jbtyp_id' => '7'
		));
		$this->command->info('Added schedule entries to example event and task on 2016-01-01.');
	}

}