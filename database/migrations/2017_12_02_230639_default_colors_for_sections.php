<?php

use Illuminate\Database\Migrations\Migration;
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
