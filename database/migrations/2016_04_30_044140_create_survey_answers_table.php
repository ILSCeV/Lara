
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
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prsn_id')->references('id')->on('persons')->unsigned();
            $table->integer('survey_id')->references('id')->on('surveys')->unsigned();
            $table->string('name', 255)->nullable()->default(NULL);
            $table->integer('club_id')->references('id')->on('clubs')->unsigned()->nullable();
            $table->integer('order')->unsigned();
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
