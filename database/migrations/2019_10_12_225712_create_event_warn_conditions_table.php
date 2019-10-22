<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventWarnConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_warn_conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->date('warn_date_start')->nullable();
            $table->date('warn_date_end')->nullable();
            $table->time('warn_time_start')->nullable();
            $table->time('warn_time_end')->nullable();
            $table->lineString('reason');
            $table->integer('section_id')->unsigned()->index();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_warn_conditions', function (Blueprint $table) {
            $table->dropPrimary('id');
        });
        Schema::dropIfExists('event_warn_conditions');
    }
}
