<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatValueToScheduleEntries extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('schedule_entries', function($table)
		{
		    $table->double('entry_statistical_weight')->after('entry_time_end');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('schedule_entries', function($table)
		{
		    $table->dropColumn('entry_statistical_weight');
		});
	}

}
