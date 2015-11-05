<?php

use Illuminate\Database\Seeder;

class PersonsTableSeeder extends Seeder
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
        DB::table('persons')->delete();
	
		/**
		 * Creating persons
		 */
        \Lara\Person::create(array('prsn_name' => 'Max',
								   'prsn_ldap_id' => '1111',
								   'prsn_status' => 'kandidat',	
								   'clb_id' => '1'));

        \Lara\Person::create(array('prsn_name' => 'Otto',	
								   'prsn_ldap_id' => '1222',
								   'prsn_status' => 'aktiv',
								   'clb_id' => '1'));							
							
        \Lara\Person::create(array('prsn_name' => 'Lena',	
								   'prsn_ldap_id' => '1333',
								   'prsn_status' => 'veteran',
								   'clb_id' => '1'));

        \Lara\Person::create(array('prsn_name' => 'THOR')); 
							
		/**
		 * Reporting result to console
		 */
		$this->command->info('Four example persons created - members Max, Otto, Lena and a guest THOR.');
    }
}
