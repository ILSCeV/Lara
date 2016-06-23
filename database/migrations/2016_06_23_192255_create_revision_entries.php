<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevisionEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revision_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('revision_id')->references('id')->on('revisions')->unsigned()->nullable();
            $table->string('changed_column_name', 255);
            $table->string('old_value', 1500)->nullable();
            $table->string('new_value', 1500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('revision_entries');
    }
}
