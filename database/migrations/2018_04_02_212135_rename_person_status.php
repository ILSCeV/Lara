<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Lara\Person;

class RenamePersonStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Person::where('prsn_status', 'resigned')->update(['prsn_status' => 'ex-member']);
        Person::where('prsn_status', 'guest')->update(['prsn_status' => 'ex-candidate']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Person::where('prsn_status', 'ex-member')->update(['prsn_status' => 'resigned']);
        Person::where('prsn_status', 'ex-candidate')->update(['prsn_status' => 'guest']);
    }
}
