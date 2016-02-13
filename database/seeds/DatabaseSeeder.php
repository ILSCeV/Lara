<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {     
        // Each table gets its own seeder
        // Example: $this->call(UserTableSeeder::class);
        $this->call(ScheduleEntriesTableSeeder::class);
        $this->call(SchedulesTableSeeder::class);
        $this->call(ClubEventsTableSeeder::class);
        $this->call(ClubsTableSeeder::class);
        $this->call(PlacesTableSeeder::class);
        $this->call(PersonsTableSeeder::class);
        $this->call(JobtypesTableSeeder::class);
    }
}
