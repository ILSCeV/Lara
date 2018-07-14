<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventWasManuallyEdited extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('club_events', function (Blueprint $table) {
            $table->boolean('was_manually_edited')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('club_events', function (Blueprint $table) {
            $table->dropColumn('was_manually_edited');
        });
    }
}
