<?php

use Illuminate\Database\Seeder;

class JobtypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('jobtypes')->delete();

        factory(Lara\Jobtype::class, 20)->create();
    }
}
