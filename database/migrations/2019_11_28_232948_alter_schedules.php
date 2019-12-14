<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Lara\Schedule;

class AlterSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->integer('club_event_id')->unsigned()->nullable();
            $table->index(['club_event_id'],'idx_schedule_to_club_event');
        });
        $schedules = Schedule::all();
        DB::transaction(function () use ($schedules) {
            $schedules->each(function (Schedule $schedule) {
                $schedule->club_event_id = $schedule->evnt_id;
                $schedule->save();
            });
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->foreign('club_event_id')->references('id')->on('club_events');
            $table->dropColumn('evnt_id');
        });

        Schema::table('shifts', function (Blueprint $table) {
            $table->foreign('schedule_id')->references('id')->on('schedules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('schedules', function (Blueprint $table) {
            $table->string('evnt_id')->index('idx_schedule_club_events');
        });

        $schedules = Schedule::all();
        DB::transaction(function () use ($schedules) {
            $schedules->each(function (Schedule $schedule) {
                $schedule->evnt_id = $schedule->club_event_id;
                $schedule->save();
            });
        });
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_club_event_id_foreign');
            $table->dropIndex('idx_schedule_to_club_event');
            $table->dropColumn('club_event_id');
        });
    }
}
