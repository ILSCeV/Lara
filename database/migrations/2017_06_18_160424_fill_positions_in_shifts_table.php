<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillPositionsInShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
        Lara\Shift::all()->each(function(Shift $shift) {
            $shift->position = NULL;
            $shift->save();
        });
    }
}
