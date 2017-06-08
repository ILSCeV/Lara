<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShiftOrderColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_entries', function ($table) {
            $table->boolean('position')->nullable();
        });
        Lara\ClubEvent::all()->each(function($event) {
            $event->shifts()->get()->each(function($shift, $key){
                $shift->position = $key;
                $shift->save();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_entries', function ($table) {
            $table->dropColumn('position');
        });
    }
}
