<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Lara\User;
use Lara\Person;
use Lara\Section;

class AddInitialBdUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $allBDusers = Config::get('bd_users.initial');

        if ($allBDusers) {
            // creating the persons is tricky. The 'max' query is probably cached, thus we need to manually
            // track the ldap_ids we are handing out. Otherwise many users end up with the same ldap_id
            $ldap_id = Person::query()->max('prsn_ldap_id') + 1;
            foreach($allBDusers as $user) {
                $user["section"] = Section::query()->where('title', 'bd-Club')->first()->id;
                $user['prsn_ldap_id'] = $ldap_id++;
                User::createNew($user);
            }
        }
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
