<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleEntriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*29*/
		Schema::create('schedule_entries', function($t) {
			$t->increments('id');
			$t->integer('schdl_id')->references('id')->on('schedules');
			$t->integer('jbtyp_id')->references('id')->on('jobtypes');
			$t->integer('prsn_id')->references('id')->on('persons')->nullable();
			$t->string('entry_user_comment');
			$t->time('entry_time_start');
			$t->time('entry_time_end');
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
		Schema::drop('schedule_entries');
	}

}
