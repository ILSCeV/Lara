<?php

use Illuminate\Database\Seeder;

class ShiftTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('shifttypes')->delete();

        factory(Lara\ShiftType::class, 20)->create();
    }
}
