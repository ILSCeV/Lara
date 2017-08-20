<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemamePlaceUidToSectionUid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("sections", function(Blueprint $table) {
            $table->renameColumn("place_uid", "section_uid");
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
            $table->renameColumn("section_uid", "place_uid");
        });
    }
}
