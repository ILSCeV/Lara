<?php

use Illuminate\Database\Seeder;

class JobtypesTableSeeder extends Seeder
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
        DB::table('jobtypes')->delete();

		/**
		 * Creating regular job types
		 */
        \Lara\Jobtype::create(array('jbtyp_title' => 'Einlass', 
									'jbtyp_statistical_weight' => '1',
									'jbtyp_is_archived' => '0'));

        \Lara\Jobtype::create(array('jbtyp_title' => 'Bar', 
									'jbtyp_statistical_weight' => '2',
									'jbtyp_is_archived' => '0'));
								
		\Lara\Jobtype::create(array('jbtyp_title' => 'Tresen', 
									'jbtyp_statistical_weight' => '2',
									'jbtyp_is_archived' => '0'));	
						
        \Lara\Jobtype::create(array('jbtyp_title' => 'Disko', 
									'jbtyp_statistical_weight' => '2',
									'jbtyp_is_archived' => '0'));

        \Lara\Jobtype::create(array('jbtyp_title' => 'Licht', 
									'jbtyp_statistical_weight' => '1',
									'jbtyp_is_archived' => '0'));


		\Lara\Jobtype::create(array('jbtyp_title' => 'Buy beer', 
									'jbtyp_statistical_weight' => '10',
									'jbtyp_is_archived' => '0'));

		\Lara\Jobtype::create(array('jbtyp_title' => 'Drink beer', 
									'jbtyp_statistical_weight' => '30',
									'jbtyp_is_archived' => '0'));

		/**
		 * Reporting result to console
		 */
		$this->command->info('Example job types created.');
    }
}
