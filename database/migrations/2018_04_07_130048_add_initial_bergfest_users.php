<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Lara\Club;
use Lara\Person;
use Lara\Section;
use Lara\User;
use Lara\utilities\RoleUtility;

class AddInitialBergfestUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $bergfestUsers = Config::get('bergfest_users.initial');

        /** @var Section $bergfest */
        $bergfest = Section::firstOrCreate(['title' => 'Bergfest']);

        $bergfest->fill([
            'section_uid' => hash("sha512", uniqid()),
            'color' => 'Orange',
            'preparationTime' => '18:00',
            'startTime' => '19:00',
            'endTime' => '01:00'
        ]);

        $bergfest->save();
        $bergfest->refresh();
        if(is_null($bergfest->club())){
            Club::create(['clb_title'=>$bergfest->title]);
        }

        RoleUtility::createRolesForNewSection($bergfest);

        if ($bergfestUsers) {
            // creating the persons is tricky. The 'max' query is probably cached, thus we need to manually
            // track the ldap_ids we are handing out. Otherwise many users end up with the same ldap_id
            $ldap_id = DB::table('persons')->select(DB::raw('max(convert( prsn_ldap_id, unsigned integer)) ldap_id'))->first()->ldap_id +1;
            foreach($bergfestUsers as $user) {
                $user["section"] = $bergfest->id;
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
