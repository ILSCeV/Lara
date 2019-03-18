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
        $personIds = Lara\Person::all(['id'])->toArray();
        $scheduleIds = Lara\Schedule::all(['id'])->toArray();
        $shiftTypeIds = Lara\ShiftType::all(['id'])->toArray();
        $faker = Faker\Factory::create('de_DE');
        
        $shifts = \Lara\Shift::all();
        $shifts->chunk(5000)->each(function (\Illuminate\Support\Collection $chunkedCollection) use (
            $personIds,
            $scheduleIds,
            $shiftTypeIds,
            $faker
        ) {
            DB::transaction(function () use ($personIds, $scheduleIds, $shiftTypeIds, $faker, $chunkedCollection) {
                $chunkedCollection->each(function (\Lara\Shift $shift) use (
                    $personIds,
                    $scheduleIds,
                    $shiftTypeIds,
                    $faker
                ) {
                    $personId = $faker->randomElement([
                        $faker->randomElement($personIds)['id'],
                        $faker->randomElement($personIds)['id'],
                        null,
                    ]);
                    $shift->fill([
                        'schedule_id'  => $faker->randomElement($scheduleIds)['id'],
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
