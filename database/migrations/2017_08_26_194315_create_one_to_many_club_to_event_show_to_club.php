<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        
        $clubEvents = DB::table('club_events')->select('club_events.id', 'club_events.evnt_show_to_club',
            'sections.title')->join('sections', 'club_events.plc_id', '=', 'sections.id')->get();
        
        /* @var $sections \Illuminate\Support\Collection */
        $sections = Section::all();
        
        /* @var $clubEvent ClubEvent */
        $clubEvents->each(function ($clubEvent) use ($sections) {
            $showToClubs = json_decode($clubEvent->evnt_show_to_club);
            if (is_null($showToClubs)) {
                $showToClubs = [$clubEvent->title];
            }
            
            return $sections->filter(function ($section) use ($showToClubs) {
                return in_array($section->title, $showToClubs);
            })->each(function ($section) use ($clubEvent) {
                DB::table('club_event_section')->insert([
                    'club_event_id' => $clubEvent->id,
                    'section_id'    => $section->id,
                ]);
            });
        });
    
        Schema::table('club_events', function (Blueprint $table) {
            $table->dropColumn('evnt_show_to_club');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('club_events', function (Blueprint $table) {
            $table->text('evnt_show_to_club')->after('plc_id');
        });
    
        $eventsWithSection = DB::table('club_events')->select('club_events.id', 'sections.title')
            ->join('club_event_section','club_event_id','=','club_events.id')
            ->join('sections', 'club_event_section.section_id', '=', 'sections.id')
            ->get();
        $eventShowToSection = [];
        foreach ($eventsWithSection as $eventWithSeciton){
            $eventShowToSection[$eventWithSeciton->id][]=$eventWithSeciton->title;
        }
    
        foreach ($eventShowToSection as $id => $sections){
            DB::table('club_events')->where('id', $id)->update(['evnt_show_to_club' => json_encode($sections)]);
        }
        
        Schema::drop('club_event_section');
    }
}
