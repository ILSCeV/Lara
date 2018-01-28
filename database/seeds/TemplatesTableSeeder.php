<?php

use Illuminate\Database\Seeder;

class TemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bdId = \DB::table('templates')->select('id')->where('title', '=', 'BD Template')->first();
        $bdSectionId = \DB::table('sections')->select('id')->where('title', '=', 'bd-Club')->first();
        \DB::table('section_template')->where('template_id','=',$bdId->id)->update(['section_id'=>$bdSectionId->id]);

        \DB::table('section_template')->where('template_id', "!=", $bdId->id)->delete();
        \DB::table('templates')->where('title', '!=', 'BD Template')->delete();
        factory(Lara\Template::class, 100)->create()
            ->each(function (Lara\Template $event) {
                // create a schedule for each event
                $event->showToSection()->sync([
                    $event->section_id,
                    Lara\Section::where('id', '!=', $event->plc_id)->inRandomOrder()->first()->id
                ]);
                $event->save();
            });
    }
}
