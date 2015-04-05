<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('club_events', function($t) {
			$t->increments('id');
			$t->string('evnt_title');
			$t->string('evnt_subtitle');
			$t->integer('plc_id')->references('id')->on('places');
			$t->date('evnt_date_start');
			$t->date('evnt_date_end');
			$t->time('evnt_time_start');
			$t->time('evnt_time_end');
			$t->longText('evnt_public_info');			/* kann leer sein, aber nicht NULL */
			$t->longText('evnt_private_details');		/* kann leer sein, aber nicht NULL */
			$t->boolean('evnt_is_private');
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
        Schema::drop('club_events');
	}

}
