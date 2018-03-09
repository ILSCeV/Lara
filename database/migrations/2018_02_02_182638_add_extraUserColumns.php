<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Lara\Person;
use Lara\User;

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

            $table->string('email')->nullable()->change();
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
        });
    }
}
