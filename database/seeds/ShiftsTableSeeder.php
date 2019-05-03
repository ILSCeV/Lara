<?php

use Illuminate\Database\Seeder;

class ShiftsTableSeeder extends Seeder
{
    
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $bdTemplateId = DB::table('templates')->select('templates.id')->where('title', '=', 'BD Template')->first();
        $shiftIds = DB::table('shift_template')->select('shift_id')->where('template_id', '=',
            $bdTemplateId->id)->get()->map(function ($shiftId) {
            return $shiftId->shift_id;
        })->toArray();
        $shiftAmount = (\Lara\ClubEvent::count() + \Lara\Template::count()) * 5;
        DB::table('shifts')->whereNotIn('id', $shiftIds)->delete();
        DB::transaction(function () use ($shiftAmount) {
            factory(Lara\Shift::class, $shiftAmount)->create();
        });
        
        $members = Lara\Person::query()->whereIn('id', \Lara\User::all(['person_id']))->get();
        $guests = Lara\Person::query()->whereNotIn('id', \Lara\User::all(['person_id']))->get();
        $schedules = Lara\Schedule::query()->whereNotNull('evnt_id')->with('event')->with('event.section')->get();
        $shiftTypeIds = Lara\ShiftType::all(['id'])->toArray();
        $faker = Faker\Factory::create('de_DE');
        
        $shifts = \Lara\Shift::all();
        $shifts->chunk(5000)->each(function (\Illuminate\Support\Collection $chunkedCollection) use (
            $members,
            $guests,
            $schedules,
            $shiftTypeIds,
            $faker
        ) {
            DB::transaction(function () use ($members, $guests, $schedules, $shiftTypeIds, $faker, $chunkedCollection) {
                $chunkedCollection->each(function (\Lara\Shift $shift) use (
                    $members,
                    $guests,
                    $schedules,
                    $shiftTypeIds,
                    $faker
                ) {
                    /** @var \Lara\Schedule $schedule */
                    $schedule = $schedules->random(1)->first();
                    $shiftSection = $schedule->event->section;
                    $shiftSectionId = $shiftSection->id;
                    $shuffledPersons = $members->shuffle();
                    $ownSectionCandidates = $shuffledPersons->filter(function (\Lara\Person $p) use ($shiftSectionId) {
                        $pSection = $p->user->section;
                        
                        return $pSection->id == $shiftSectionId;
                    })->map(function (\Lara\Person $p) {
                        return $p->id;
                    })->take(2);
                    $otherSectionCandidate = $shuffledPersons->first(function (\Lara\Person $p) use ($shiftSectionId) {
                        $pSection = $p->user->section;
                        
                        return $pSection->id != $shiftSectionId;
                    })->id;
                    $candidates = collect([$guests->random(1)->first()->id])
                        ->merge($ownSectionCandidates)
                        ->merge([$otherSectionCandidate, null])
                        ->toArray();
                    $personId = $faker->randomElement($candidates);
                    $shift->fill([
                        'schedule_id'  => $schedule->id,
                        'shifttype_id' => $faker->randomElement($shiftTypeIds)['id'],
                        'person_id'    => $personId,
                        'comment'      => $personId ? $faker->randomElement([$faker->sentence, ""]) : "",
                    ]);
                    $shift->save();
                });
            }
            );
        });
    }
}
