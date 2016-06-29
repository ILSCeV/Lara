<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator_id')->references('id')->on('persons')->unsigned();
            $table->string('title', 255);
            $table->string('description', 1500)->nullable()->default(NULL);
            $table->timestamp('deadline');
            $table->string('password');
            $table->boolean('is_private');
            $table->boolean('is_anonymous');
            $table->boolean('show_results_after_voting');
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
        Schema::drop('surveys');
    }
}
