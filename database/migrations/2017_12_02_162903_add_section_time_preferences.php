<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSectionTimePreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("sections", function(Blueprint $table) {
            $table->time('preparationTime')->default("20:00");
            $table->time('startTime')->default('21:00');
            $table->time('endTime')->default('1:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("sections", function(Blueprint $table) {
            $table->dropColumn('preparationTime');
            $table->dropColumn('startTime');
            $table->dropColumn('endTime');
        });
    }
}
