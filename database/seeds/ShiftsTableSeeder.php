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
        $bdTemplateId = DB::table('schedules')->select('schedules.id')->where('schdl_title','=','BD Template')->first();
        DB::table('shifts')->where('schedule_id','!=',$bdTemplateId->id)->delete();
        factory(Lara\Shift::class, 500)->create();
    }
}
