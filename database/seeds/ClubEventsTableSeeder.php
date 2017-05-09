<?php

use Illuminate\Database\Seeder;
use Faker\Factory;

class ClubEventsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('club_events')->delete();
        \DB::table('schedules')->delete();
        factory(Lara\ClubEvent::class, 100)->create()
            ->each(function(Lara\ClubEvent $event) {
                // create a schedule for each event
                $event->getSchedule()->save(factory(Lara\Schedule::class)->make());
            });
    }
}
