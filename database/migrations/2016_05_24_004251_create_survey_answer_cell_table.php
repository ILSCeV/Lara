<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyAnswerCellTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_answer_cell', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('survey_answer_id')->references('id')->on('survey_answer')->unsigned();
            $table->integer('number')->unsigned();
            $table->string('content', 1500);
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
        Schema::drop('survey_answer_cell');
    }
}
