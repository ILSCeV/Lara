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
        $shiftTypeStart = '20:00:00';
        $shiftTypeEnd = '01:00:00';
        $shiftTypeNames = ['AV', 'Musik', 'Bier', 'Bar', 'Einlass'];
        $shiftTypes = \DB::table('shifttypes')->select('shifttypes.id')->whereIn('title', $shiftTypeNames)
            ->where('start', '=', $shiftTypeStart)
            ->where('end', '=', $shiftTypeEnd)->get()->map(function ($type){
                return $type->id;
            })->toArray();
        $bdTemplateId = DB::table('templates')->select('templates.id')->where('title','=','BD Template')->first();
        $shiftIds = DB::table('shift_template')->select('shift_id')->where('template_id','=',$bdTemplateId->id)->get()->map(function ($shiftId){return $shiftId->shift_id;})->toArray();
        $bdShiftTypes = \DB::table('shifts')->select('shifttype_id')->whereIn('id',$shiftIds)->get()->map(function ($shiftId){return $shiftId->shifttype_id;})->toArray();
        $shiftTypes = array_merge($shiftTypes,$bdShiftTypes);
        \DB::table('shifttypes')->whereNotIn('id',$shiftTypes)->delete();

        factory(Lara\ShiftType::class, 50)->create();
    }
}
