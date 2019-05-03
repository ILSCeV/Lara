<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('club_events', function (Blueprint $table) {
            $table->index(['evnt_date_start','evnt_date_end'],'idx_event_start_end');
        });
        Schema::table('shifts', function (Blueprint $table) {
            $table->index(['schedule_id'],'idx_shift_schedule');
        });
        Schema::table('schedules', function (Blueprint $table) {
            $table->index(['evnt_id'],'idx_schedule_club_events');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->index(['name'],'idx_user_name');
            $table->index(['section_id'],'idx_user_sections');
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
            $table->dropIndex('idx_event_start_end');
        });
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropIndex('idx_shift_schedule');
        });
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex('idx_schedule_club_events');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_user_name');
            $table->dropIndex('idx_user_sections');
        });
    }
}
