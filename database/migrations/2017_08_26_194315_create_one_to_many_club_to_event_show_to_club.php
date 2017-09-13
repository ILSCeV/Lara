<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Lara\Club;
use Lara\ClubEvent;

class CreateOneToManyClubToEventShowToClub extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('join_events_to_club', function (Blueprint $table) {
            $table->bigInteger('club_id');
            $table->bigInteger('event_id');
        });
        
        $clubEvents = ClubEvent::all();
        
        /* @var $clubs \Illuminate\Support\Collection */
        $clubs = Club::activeClubs()->get();
        
        /* @var $clubEvent ClubEvent */
        $joinClubsToEvents = $clubEvents->flatMap(function($clubEvent) use ($clubs) {
           $showToClubs = json_decode($clubEvent->evnt_show_to_club);
           return $clubs->filter(function($club) use ($showToClubs){
             return in_array($club->clb_title, $showToClubs);
            })->map(function($club) use ($clubEvent) {
              $joinClubToEvent = new \Lara\JoinEventClub();
              $joinClubToEvent->club_id=$club->id;
              $joinClubToEvent->event_id=$clubEvent->id;
              return $joinClubToEvent;
           });
        });
        
        /* @var $joinClubToEvent \Lara\JoinEventClub */
        foreach ($joinClubsToEvents as $joinClubToEvent){
            $joinClubToEvent->save();
        }
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('join_events_to_club');
    }
}
