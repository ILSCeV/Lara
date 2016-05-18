<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShowToFilter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Add a filter telling Lara what clubs to show this event to.
         */
        Schema::table('club_events', function (Blueprint $table) {
            $table->text('evnt_show_to_club')->after('plc_id');
            // Can be empty, but not NULL. 
            // Empty = this is an old event from the times filter wasn't implemented, 
            // so use plc_id instead (implemented in the controller)
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
             $table->dropColumn('evnt_show_to_club');
        }); 
    }
}
