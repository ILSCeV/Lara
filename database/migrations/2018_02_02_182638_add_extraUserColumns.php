<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraUserColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('person_id')->references('id')->on('persons');
            $table->integer('section_id')->references('id')->on('section');
            $table->string('status');
            $table->string('group');
        });

        Lara\Person::all()->each(function(Person $person) {
            User::createFromPerson($person);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('person_id');
            $table->dropColumn('section_id');
            $table->dropColumn('status');
            $table->dropColumn('group');
        });
    }
}
