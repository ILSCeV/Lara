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
        $bc_club = Lara\Section::find(1);
        $bc_cafe = Lara\Section::find(2);
        $bd_club = Lara\Section::find(3);

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
