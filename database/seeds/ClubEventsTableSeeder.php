<?php

use Illuminate\Database\Seeder;

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
        \DB::table('schedules')->where('schdl_title', '!=', 'BD Template')->delete();
        \DB::transaction(function () {
            /** @var \Illuminate\Support\Collection/\Lara\ClubEvent $clubEvents */
            $clubEvents = factory(Lara\ClubEvent::class, 5000)->create()
                ->map(function (Lara\ClubEvent $event) {
                    // create a schedule for each event
                    $event->schedule()->save(factory(Lara\Schedule::class)->make());
                    $event->showToSection()->sync([
                        $event->plc_id,
                        Lara\Section::where('id', '!=', $event->plc_id)->inRandomOrder()->first()->id,
                    ]);
                    
                    return $event;
                });
            $clubEvents->each(function (\Lara\ClubEvent $event) {
                $event->save();
            });
        });
    }
}
