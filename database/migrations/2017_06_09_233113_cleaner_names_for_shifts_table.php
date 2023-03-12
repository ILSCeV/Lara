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
        alterShiftTable("schdl_id", "schedule_id");
        alterShiftTable("jbtyp_id", "shifttype_id");
        alterShiftTable("prsn_id", "person_id");
        alterShiftTable("entry_user_comment", "comment");
        alterShiftTable("entry_statistical_weight", "statistical_weight");
        alterShiftTable("entry_time_start", "start");
        alterShiftTable("entry_time_end", "end");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        alterShiftTable("schedule_id", "schdl_id");
        alterShiftTable("shifttype_id", "jbtyp_id");
        alterShiftTable("person_id", "prsn_id");
        alterShiftTable("comment", "entry_user_comment");
        alterShiftTable("statistical_weight", "entry_statistical_weight");
        alterShiftTable("start", "entry_time_start");
        alterShiftTable("end", "entry_time_end");

    }

    /**
    * @param string $from
    * @param string $to
    * @return void
    */
    private function alterShiftTable($from, $to)
    {
        Schema::table("shifts", function (Blueprint $table) {
            $table->renameColumn($from, $to);
        });
    }
}
