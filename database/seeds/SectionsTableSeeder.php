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
        
        \DB::table('sections')->insert([
            0 => [
                'title' => 'bc-Club',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'section_uid' => hash("sha512", uniqid()),
                'color' => "Red"

            ],
            1 => [
                'title' => 'bc-Café',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'section_uid' => hash("sha512", uniqid()),
                'color' => "Blue"
            ],
            2 => [
                'title' => 'bd-Club',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'section_uid' => hash("sha512", uniqid()),
                "color" => "Green"
            ]
        ]);
        
        
    }
}
