<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobtypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
       Schema::create('jobtypes', function($t) {
			$t->increments('id');
			$t->string('jbtyp_title');
			$t->time('jbtyp_time_start');
			$t->time('jbtyp_time_end');
			$t->boolean('jbtyp_needs_preparation');
			$t->double('jbtyp_statistical_weight');
			$t->boolean('jbtyp_is_archived');
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
        Schema::drop('jobtypes');
	}

}
