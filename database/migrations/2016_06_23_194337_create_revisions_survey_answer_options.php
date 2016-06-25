<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevisionsSurveyAnswerOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisions_survey_answer_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('object_id')->references('id')->on('survey_answer_options')->unsigned()->nullable();
            $table->integer('revision_id')->references('id')->on('revisions')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('revisions_survey_answer_options');
    }
}
