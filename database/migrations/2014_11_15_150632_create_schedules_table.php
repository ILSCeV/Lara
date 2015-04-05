<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
      Schema::create('schedules', function($t) {
			$t->increments('id');
			$t->string('schdl_title')->nullable()->default(NULL);
			$t->time('schdl_time_preparation_start')->nullable()->default(NULL);
			$t->date('schdl_due_date')->nullable()->default(NULL);
			$t->string('schdl_password');	
			$t->string('evnt_id')->references('id')->on('club_events')->nullable();	/* if NULL then it's a "task", else it's a "schedule" for that ClubEvent */
			$t->boolean('schdl_is_template');
			$t->boolean('schdl_show_in_week_view'); 
			$t->timestamps();			
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
