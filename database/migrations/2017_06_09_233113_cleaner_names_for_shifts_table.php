<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanerNamesForShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("shifts", function(Blueprint $table) {
            $table->renameColumn("schdl_id", "schedule_id");
            $table->renameColumn("jbtyp_id", "shifttype_id");
            $table->renameColumn("prsn_id", "person_id");
            $table->renameColumn("entry_user_comment", "comment");
            $table->renameColumn("entry_statistical_weight", "statistical_weight");
            $table->renameColumn("entry_time_start", "start");
            $table->renameColumn("entry_time_end", "end");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("shifts", function(Blueprint $table) {
            $table->renameColumn("schedule_id", "schdl_id");
            $table->renameColumn("shifttype_id", "jbtyp_id");
            $table->renameColumn("person_id", "prsn_id");
            $table->renameColumn("comment", "entry_user_comment");
            $table->renameColumn("statistical_weight", "entry_statistical_weight");
            $table->renameColumn("start", "entry_time_start");
            $table->renameColumn("end", "entry_time_end");
        });
    }
}
