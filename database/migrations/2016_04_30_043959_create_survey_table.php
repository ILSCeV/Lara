<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prsn_id')->references('id')->on('persons')->unsigned();
            $table->string('title', 255);
            $table->string('description', 1500)->nullable()->default(NULL);
            $table->timestamp('deadline');
            $table->timestamps();
            $table->date('in_calendar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('survey');
    }
}
