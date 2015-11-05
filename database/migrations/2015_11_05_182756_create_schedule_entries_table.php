<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schdl_id')->references('id')->on('schedules');
            $table->integer('jbtyp_id')->references('id')->on('jobtypes');
            $table->integer('prsn_id')->references('id')->on('persons')->nullable();
            $table->string('entry_user_comment');
            $table->time('entry_time_start');
            $table->time('entry_time_end');
            $table->double('entry_statistical_weight');
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
        Schema::drop('schedule_entries');
    }
}
