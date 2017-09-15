<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Lara\Club;
use Lara\ClubEvent;
use Lara\EventsSections;
use Lara\Section;

class CreateOneToManyClubToEventShowToClub extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club_event_section', function (Blueprint $table) {
            $table->integer('club_event_id')->unsigned()->index();
            $table->integer('section_id')->unsigned()->index();
            
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('club_event_id')->references('id')->on('club_events')->onDelete('cascade');
        });
        
        $clubEvents = ClubEvent::all();
        
        /* @var $sections \Illuminate\Support\Collection */
        $sections = Section::all();
        
        /* @var $clubEvent ClubEvent */
        $clubEvents->each(function($clubEvent) use ($sections) {
           $showToClubs = json_decode($clubEvent->evnt_show_to_club);
           return $sections->filter(function($section) use ($showToClubs){
             return in_array($section->title, $showToClubs);
            })->each(function($section) use ($clubEvent) {
              DB::table('club_event_section')->insert(array('club_event_id' => $clubEvent->id, 'section_id'=>$section->id));
           });
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('club_event_section');
    }
}
