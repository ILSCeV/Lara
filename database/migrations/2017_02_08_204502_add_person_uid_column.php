<?php
use \Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->string('prsn_uid');
        });

        $persons = Person::all();
        foreach ($persons as $person) {
            $person->prsn_uid = hash("sha512", uniqid());
            $person->save();
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
    }
}
