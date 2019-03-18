<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SectionsTableSeeder extends Seeder
{
    
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        
        
        \DB::table('sections')->delete();
        
        $sectionDefinitions = collect([
            'bc-Club' => 'Red',
            'bc-Café' => 'Blue',
            'bh-Club' => 'Green',
            'bd-Club' => 'Teal',
            'bi-Club' => 'Blue-Grey',
        ]);
        $sections = $sectionDefinitions->map(function ($color, $sec) {
            return $this->mkSection($sec, $color);
        });
        
        $sections->each(function (\Lara\Section $section) {
            $section->save();
        });
        
        
    }
    
    private function mkSection($sectionName, $color)
    {
        return new \Lara\Section([
            'title'       => $sectionName,
            'color'       => $color,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
            'section_uid' => hash("sha512", uniqid()),
            'preparationTime' => '20:00',
            'startTime' => '21:00',
            'endTime' => '01:00'
        ]);
    }
}
