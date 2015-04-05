<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/* S.: scheduleId, jobTypeId and comment deleted. --> Ticket #28 version 1.3 */
		/* S.: table renamed: jobs -> persons */
      Schema::create('persons', function($t) {
			$t->increments('id');
			$t->string('prsn_name');												/* must not be empty, needs a guard later in the add-function*/
			$t->string('prsn_ldap_id')->nullable()->default(NULL); 					/* filled via LDAP */	
			$t->string('prsn_status');
			$t->integer('clb_id')->references('id')->on('clubs')->default('1');	/* default = '1' = "extern". M. */
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
        Schema::drop('persons');
	}

}
