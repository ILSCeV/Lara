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
        DB::table('schedule_entries')->delete();
        factory(Lara\Shift::class, 500)->create();
    }
}
