<?php

use Illuminate\Database\Migrations\Migration;
use Lara\Club;
use Lara\Section;

class DefaultColorsForSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $section = Section::where('title', '=', 'bd-Club')->first();
        if (is_null($section)) {
            $section = new Section();
            $section->title = 'bd-Club';
            $section->section_uid = hash("sha512", uniqid());
            $section->save();
            $club = new Club();
            $club->clb_title = $section->title;
            $club->save();
        }

        /** @var sections \Illuminate\Database\Eloquent\Collection[Section] */
        $sections = Section::all();
        foreach ($sections as $section) {
            if ($section->title == 'bc-Club') {
                $section->color = 'Red';
            }
            if ($section->title == 'bc-Café') {
                $section->color = 'Blue';
            }
            if ($section->title == 'bd-Club') {
                $section->color = 'Green';
            }
            $section->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        /** @var sections \Illuminate\Database\Eloquent\Collection[Section] */
        $sections = Section::all();
        foreach ($sections as $section) {
            $section->color = null;
            $section->update();
        }
    }
}
