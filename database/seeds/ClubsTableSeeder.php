<?php

use Illuminate\Database\Seeder;

class ClubsTableSeeder extends Seeder
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
        DB::table('clubs')->delete();

		/**
		 * Creating clubs
		 */
        \Lara\Club::create(array('clb_title' => '-'));			// Default - do not delete! 
        														// This value is needed for production Lara to run
		
		/**
		 * Reporting result to console
		 */
		$this->command->info('Default club created.');
    }
}
