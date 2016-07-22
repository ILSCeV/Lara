
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyAnswersTable extends Migration
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
            // not able due to no corresponding users and ldap_id
//            $table->integer('creator_id')->references('prsn_ldap_id')->on('persons')->unsigned()->nullable();
            $table->integer('creator_id')->nullable();
            $table->integer('survey_id')->references('id')->on('surveys')->unsigned();
            $table->string('name', 255)->nullable()->default(NULL);
            $table->string('club')->nullable();
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
        Schema::drop('survey_answers');
    }
}
