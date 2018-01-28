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
        \DB::table('shifttypes')->whereNotIn('id',$shiftTypes)->delete();

        factory(Lara\ShiftType::class, 20)->create();
    }
}
