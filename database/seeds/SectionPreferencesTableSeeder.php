<?php

use Illuminate\Database\Seeder;

class SectionPreferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bc_club = Lara\Section::where('title', 'bc-Club')->first();
        $bc_cafe = Lara\Section::where('title', 'bc-Cafe')->first();
        $bd_club = Lara\Section::where('title', 'bd-Club')->first();

        $bc_club->fill([
            'preparationTime' => '20:00',
            'startTime' => '21:00',
            'endTime' => '01:00'
        ]);
        $bc_cafe->fill([
            'preparationTime' => '10:45',
            'startTime' => '12:00',
            'endTime' => '17:00'
        ]);
        $bd_club->fill([
            'preparationTime' => '20:00',
            'startTime' => '21:00',
            'endTime' => '01:00'
        ]);

        $bc_club->save();
        $bc_cafe->save();
        $bd_club->save();
    }
}
