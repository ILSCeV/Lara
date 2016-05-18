<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 * Each table gets its own method.
	 * Tables will be cleared and filled with example entries.
	 * The entries were generated using iSeed https://github.com/orangehill/iseed
	 *
	 * Generation: 'php artisan iseed club_events,schedules,persons,jobtypes,places,clubs,schedule_entries'
	 *
	 * Seeding: 'php artisan migrate:refresh && composer dumpautoload && php artisan db:seed'
	 *
	 * @return void
	 */
	public function run() {
		Eloquent::unguard();

		#iseed_start
		// iSeed will store the calls for newly generated seeds here
		#iseed_end
	}
}
