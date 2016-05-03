
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_answer', function (Blueprint $table) {
            $table->integer('survey_question_id')->references('survey_id')->on('survey_question')->unsigned();
            $table->integer('survey_question_number')->references('number')->on('survey_question')->unsigned();
            $table->primary(['survey_question_id', 'survey_question_number']);
            $table->integer('prsn_id')->references('persons')->on('id')->unsigned();
            $table->string('name', 255);
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
        Schema::drop('survey_answer');
    }
}
