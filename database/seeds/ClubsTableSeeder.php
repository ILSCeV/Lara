<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ClubsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('clubs')->delete();
        
        \Lara\Section::query()->each(function(\Lara\Section $section) {
            $club = new \Lara\Club(['clb_title'=>$section->title]);
            $club->save();
        });
        
        factory(Lara\Club::class, 10)->create();
    }
}
