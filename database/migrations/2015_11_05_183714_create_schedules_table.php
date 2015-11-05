<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('schdl_title')->nullable()->default(NULL);
            $table->time('schdl_time_preparation_start')->nullable()->default(NULL);
            $table->date('schdl_due_date')->nullable()->default(NULL);
            $table->string('schdl_password');   
            $table->string('evnt_id')->references('id')->on('club_events')->nullable(); 
                    // Old Lara 1.5 rule: if evnt_id = NULL then it's a "task", 
                    //                    else it's a "schedule" for that ClubEvent
            $table->binary('entry_revisions')->nullable();
                    // Adding a blob equivalent column for entry change history that will be json encoded
            $table->boolean('schdl_is_template');
            $table->boolean('schdl_show_in_week_view'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('schedules');
    }
}
