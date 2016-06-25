<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevisionsSurveyQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisions_survey_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('object_id')->references('id')->on('survey_questions')->unsigned()->nullable();
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
        Schema::drop('revisions_survey_questions');
    }
}
