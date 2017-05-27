<?php

use Illuminate\Database\Seeder;

class ScheduleEntriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('schedule_entries')->delete();
        factory(Lara\ScheduleEntry::class, 500)->create();
    }
}
