<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lara\Person;

class AddPersonUidColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** add column for person uid used for ical feed */
        Schema::table('persons', function (Blueprint $table) {
            $table->longText('prsn_uid');
        });

        Schema::table('places', function (Blueprint $table){
            $table->longText('place_uid');
        });

        $persons = Person::whereNotNull("prsn_ldap_id")->get();
        foreach ($persons as $person) {
            $person->prsn_uid = hash("sha512", uniqid());
            $person->save();
        }
        $places = \Lara\Place::all();
        foreach ($places as $place){
            $place->place_uid = hash("sha512", uniqid());
            $place->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('prsn_uid');
        });
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('place_uid');
        });
    }
}
