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
            $table->renameColumn("schdl_id", "schedule_id")
                ->renameColumn("jbtyp_id", "shifttype_id")
                ->renameColumn("prsn_id", "person_id")
                ->renameColumn("entry_user_comment", "comment")
                ->renameColumn("entry_statistical_weight", "statistical_weight")
                ->renameColumn("entry_time_start", "start")
                ->renameColumn("entry_time_end", "end");
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
            $table->renameColumn("schedule_id", "schdl_id")
                ->renameColumn("shifttype_id", "jbtyp_id")
                ->renameColumn("person_id", "prsn_id")
                ->renameColumn("comment", "entry_user_comment")
                ->renameColumn("statistical_weight", "entry_statistical_weight")
                ->renameColumn("start", "entry_time_start")
                ->renameColumn("end", "entry_time_end");
        });
    }
}
