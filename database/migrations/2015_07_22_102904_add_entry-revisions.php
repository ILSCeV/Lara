<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEntryRevisions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Adding a blob equivalent column for entry change history that will be json encoded
		Schema::table('schedules', function($table)
		{
		    $table->binary('entry_revisions')->after('evnt_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('schedules', function($table)
		{
		    $table->dropColumn('entry_revisions');
		});
	}

}
