<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClOnlyAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('club_events', function (Blueprint $table) {
           $table->boolean('cl_only_visible')->default(false);
        });
        Schema::table('templates', function (Blueprint $table) {
            $table->boolean('cl_only_visible')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('club_events', function (Blueprint $table) {
            $table->dropColumn('cl_only_visible');
        });
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn('cl_only_visible');
        });
    }
}
