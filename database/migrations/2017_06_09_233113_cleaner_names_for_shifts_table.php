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
        $this->alterShiftTable("schdl_id", "schedule_id");
        $this->alterShiftTable("jbtyp_id", "shifttype_id");
        $this->alterShiftTable("prsn_id", "person_id");
        $this->alterShiftTable("entry_user_comment", "comment");
        $this->alterShiftTable("entry_statistical_weight", "statistical_weight");
        $this->alterShiftTable("entry_time_start", "start");
        $this->alterShiftTable("entry_time_end", "end");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        $this->alterShiftTable("schedule_id", "schdl_id");
        $this->alterShiftTable("shifttype_id", "jbtyp_id");
        $this->alterShiftTable("person_id", "prsn_id");
        $this->alterShiftTable("comment", "entry_user_comment");
        $this->alterShiftTable("statistical_weight", "entry_statistical_weight");
        $this->alterShiftTable("start", "entry_time_start");
        $this->alterShiftTable("end", "entry_time_end");

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
