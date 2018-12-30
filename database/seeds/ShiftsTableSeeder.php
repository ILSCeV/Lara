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
        $bdTemplateId = DB::table('templates')->select('templates.id')->where('title','=','BD Template')->first();
        $shiftIds = DB::table('shift_template')->select('shift_id')->where('template_id','=',$bdTemplateId->id)->get()->map(function ($shiftId){return $shiftId->shift_id;})->toArray();
        $shiftAmount = (\Lara\ClubEvent::count() + \Lara\Template::count())*5;
        DB::table('shifts')->whereNotIn('id',$shiftIds)->delete();
        factory(Lara\Shift::class, $shiftAmount)->create();
    }
}
