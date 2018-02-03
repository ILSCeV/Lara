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

        foreach (Lara\Person::all() as $person) {
            User::create([
                'name' => $person->prsn_name,
                'email' => '',
                'section_id' => $person->club->section(),
                'person_id' => $person->id,
                'status' => $person->prsn_status,
                'group' => $person->club->clb_title
            ]);
        }

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
