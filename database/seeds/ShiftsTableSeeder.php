<?php

use Illuminate\Database\Seeder;

class ShiftsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('shifts')->delete();
        factory(Lara\Shift::class, 500)->create();
    }
}
