<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanerColumnNamesForShifttypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("shifttypes", function(Blueprint $table) {
            $table->renameColumn('jbtyp_title', 'title' );
            $table->renameColumn('jbtyp_time_start', 'start');
            $table->renameColumn('jbtyp_time_end', 'end');
            $table->renameColumn('jbtyp_needs_preparation', 'needs_preparation');
            $table->renameColumn('jbtyp_statistical_weight', 'statistical_weight');
            $table->renameColumn('jbtyp_is_archived', 'is_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("shifttypes", function(Blueprint $table) {
            $table->renameColumn('title', 'jbtyp_title' );
            $table->renameColumn('start', 'jbtyp_time_start');
            $table->renameColumn('end', 'jbtyp_time_end');
            $table->renameColumn('needs_preparation', 'jbtyp_needs_preparation');
            $table->renameColumn('statistical_weight', 'jbtyp_statistical_weight');
            $table->renameColumn('is_archived', 'jbtyp_is_archived');
        });
    }
}
