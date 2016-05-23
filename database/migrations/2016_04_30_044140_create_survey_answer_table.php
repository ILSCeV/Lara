
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
            $table->integer('prsn_id')->references('id')->on('persons')->unsigned();
            $table->integer('survey_id')->references('id')->on('survey')->unsigned();
            $table->string('name', 100)->nullable()->default(NULL);
            $table->string('club', 100)->nullable()->default(NULL);
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
