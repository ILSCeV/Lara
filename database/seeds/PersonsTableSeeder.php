<?php

use Illuminate\Database\Seeder;

class PersonsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('persons')->delete();
        
        \DB::table('persons')->insert(array (
            0 => 
            array (
                'id' => 1,
                'prsn_name' => 'Max',
                'prsn_ldap_id' => '1111',
                'prsn_status' => 'candidate',
                'prsn_uid' => hash("sha512", uniqid()),
                'clb_id' => 1,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            1 => 
            array (
                'id' => 2,
                'prsn_name' => 'Otto',
                'prsn_ldap_id' => '1222',
                'prsn_status' => 'member',
                'prsn_uid' => hash("sha512", uniqid()),
                'clb_id' => 1,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            2 => 
            array (
                'id' => 3,
                'prsn_name' => 'Lena',
                'prsn_ldap_id' => '1333',
                'prsn_status' => 'veteran',
                'prsn_uid' => hash("sha512", uniqid()),
                'clb_id' => 1,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
            3 => 
            array (
                'id' => 4,
                'prsn_name' => 'THOR',
                'prsn_ldap_id' => NULL,
                'prsn_status' => '',
                'prsn_uid' => hash("sha512", uniqid()),
                'clb_id' => 1,
                'created_at' => '2016-05-18 17:13:26',
                'updated_at' => '2016-05-18 17:13:26',
            ),
        ));
        
        
    }
}
