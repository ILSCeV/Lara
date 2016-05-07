
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
            $table->increments('id');
            $table->integer('survey_question_id')->references('id')->on('survey_question')->unsigned();
            $table->integer('prsn_id')->references('id')->on('persons')->unsigned();
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
