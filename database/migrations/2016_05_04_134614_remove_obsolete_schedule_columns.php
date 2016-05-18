<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Lara\Schedule;
use Lara\ClubEvent;

class RemoveObsoleteScheduleColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * After switch to Lara 2.0 older Tasks (schedules without events) get an event.
     * 
     * @return void
     */
    public function up()
    {
        /**
         * Parse the whole DB, adding a new event to each schedule without one.
         * This practically converts "old Tasks" into "normal" Events of a certain type.
         */
        $tasks = Schedule::where('evnt_id', '=', NULL)
                         ->get();

        foreach ($tasks as $task) {            
            $event = new ClubEvent;   

            // copying attributes form the schedule or filling with defaults otherwise
            $event->evnt_title           = $task->schdl_title;                
            $event->plc_id               = 1; // Set to "bc-Club" - doesn't matter who created it, filter will be set to both.
            $event->evnt_date_start      = $task->schdl_due_date; 
            $event->evnt_date_end        = $task->schdl_due_date;
            $event->evnt_time_start      = mktime(0, 0, 0);
            $event->evnt_time_end        = mktime(0, 0, 0);
            $event->evnt_is_private      = 1; // 1 = show only to logged in members

            // Fill event type marker by trying to match a substring in schedule's title
            if (strpos($task->schdl_title, 'Flyer') !== false) {
                $event->evnt_type = 7;
            } 
            elseif (strpos($task->schdl_title, 'Fluten') !== false) {
                $event->evnt_type = 6;
            } 
            elseif (strpos($task->schdl_title, 'VVK') !== false) {
                $event->evnt_type = 8;
            }
            else
            {
                $event->evnt_type = 9;
            }

            // can safely show tasks to both clubs
            $filter = []; 
            array_push($filter, 'bc-Club'); 
            array_push($filter, 'bc-Café');
            $event->evnt_show_to_club = json_encode($filter, true);

            // add event to the DB
            $event->save();

            // Copy newly created task ID to the schedule reference to connect them together.
            $task->evnt_id = $event->id;
            $task->save();
        }

        /**
         * Then we remove columns we no longer need from the schedules table.
         */
        Schema::table('schedules', function ($table) {
            $table->dropColumn(['schdl_due_date', 'schdl_show_in_week_view']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /**
         * Just restore the DB, because we can't find old tasks any longer.
         */
        Schema::table('schedules', function ($table) {
            $table->date('schdl_due_date')->nullable()->default(NULL);
            $table->boolean('schdl_show_in_week_view'); 
        });  
    }
}
