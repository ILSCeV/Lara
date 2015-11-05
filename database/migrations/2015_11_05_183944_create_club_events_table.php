<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('evnt_type');
            $table->string('evnt_title');
            $table->string('evnt_subtitle');
            $table->integer('plc_id')->references('id')->on('places');
            $table->date('evnt_date_start');
            $table->date('evnt_date_end');
            $table->time('evnt_time_start');
            $table->time('evnt_time_end');
            $table->longText('evnt_public_info');           
                    // Can be empty, but not NULL
            $table->longText('evnt_private_details');       
                    // Can be empty, but not NULL
            $table->boolean('evnt_is_private');
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
        Schema::drop('club_events');
    }
}
