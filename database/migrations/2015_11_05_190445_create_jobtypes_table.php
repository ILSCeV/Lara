<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobtypes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jbtyp_title');
            $table->time('jbtyp_time_start');
            $table->time('jbtyp_time_end');
            $table->boolean('jbtyp_needs_preparation');
            $table->double('jbtyp_statistical_weight');
            $table->boolean('jbtyp_is_archived');
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
        Schema::drop('jobtypes');
    }
}
