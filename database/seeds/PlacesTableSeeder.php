<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PlacesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('places')->delete();
        
        \DB::table('places')->insert([
            0 => [
                'id' => 1,
                'plc_title' => 'bc-Club',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'place_uid' => hash("sha512", uniqid())
            ],
            1 => [
                'id' => 2,
                'plc_title' => 'bc-Café',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'place_uid' => hash("sha512", uniqid())
            ]
        ]);
        
        
    }
}
