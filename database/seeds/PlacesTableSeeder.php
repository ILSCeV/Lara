<?php

use Illuminate\Database\Seeder;

class PlacesTableSeeder extends Seeder
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
        DB::table('places')->delete();

		/**
		 * Creating places
		 */

        \Lara\Place::create(array('plc_title' => '-'));			// Default - do not delete!
        														// This value is needed for production Lara to run.
		
		/**
		 * Reporting result to console
		 */
		$this->command->info('Default location created.');
    }
}
