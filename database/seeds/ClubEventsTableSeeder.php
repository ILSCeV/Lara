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
        \DB::table('schedules')->where('schdl_title','!=','BD Template')->delete();
        factory(Lara\ClubEvent::class, 5000)->create()
            ->map(function(Lara\ClubEvent $event) {
                // create a schedule for each event
                $event->getSchedule()->save(factory(Lara\Schedule::class)->make());
                $event->showToSection()->sync([
                    $event->plc_id,
                    Lara\Section::where('id', '!=', $event->plc_id)->inRandomOrder()->first()->id
                ]);
                return $event;
            })->each(function(Lara\ClubEvent $event) {
              $event->save();
            });
    }
}
