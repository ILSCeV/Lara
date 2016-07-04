<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevisionObjectRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revision_object_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('revision_id')->references('id')->on('revisions')->unsigned();
            $table->integer('object_id')->unsigned;
            $table->string('object_name', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('revision_object_relations');
    }
}
