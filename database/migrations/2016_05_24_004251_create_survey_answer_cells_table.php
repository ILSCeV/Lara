<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyAnswerCellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_answer_cells', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('survey_answer_id')->references('id')->on('survey_answers')->unsigned();
            $table->integer('survey_question_id')->references('id')->on('survey_questions')->unsigned();
            $table->string('answer', 1500);
            $table->softDeletes();
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
        Schema::drop('survey_answer_cells');
    }
}
