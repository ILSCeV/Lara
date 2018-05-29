<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Lara\Settings;
use Lara\Person;

class MoveSettingToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function(Blueprint $table) {
            $table->integer('user_id')->references('id')->on('users');
        });

        Settings::all()->each(function(Settings $settings) {
            $person = Person::query()->where('prsn_ldap_id', $settings->userId)->first();
            if (!$person) {
                return;
            }
            $settings->user_id = $person->user->id;
            $settings->save();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
