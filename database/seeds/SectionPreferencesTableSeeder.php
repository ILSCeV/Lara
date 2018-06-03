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
        
        $bc_cafe = Lara\Section::query()->where('title', 'bc-Café')->first();
        
        $bc_cafe->fill([
            'color' => 'Blue',
            'preparationTime' => '10:45',
            'startTime' => '12:00',
            'endTime' => '17:00'
        ]);

        
        $bc_cafe->save();
        
    }
}
