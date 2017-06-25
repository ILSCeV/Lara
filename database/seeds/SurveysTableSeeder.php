<?php

use Illuminate\Database\Seeder;

class SurveysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('surveys')->delete();
        
        factory(Lara\Survey::class, 20)->create();
        
    }
}
