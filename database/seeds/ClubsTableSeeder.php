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

        DB::table('clubs')->insert([
            ['clb_title' => 'bc-Club', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'id' => 1],
            ['clb_title' => 'bc-Café', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'id' => 2]
        ]);
        factory(Lara\Club::class, 10)->create();
    }
}
