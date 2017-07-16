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
                'plc_title' => 'bc-Club',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'place_uid' => hash("sha512", uniqid())
            ],
            1 => [
                'plc_title' => 'bc-Café',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'place_uid' => hash("sha512", uniqid())
            ],
            2 => [
                'plc_title' => 'bd-Club',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'place_uid' => hash("sha512", uniqid())
            ]
        ]);
        
        
    }
}
