<?php

use Illuminate\Database\Seeder;
use Lara\Person;

class PersonsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('persons')->delete();
        \DB::table('role_user')->delete();
        \DB::table('users')->delete();
        factory(Lara\Person::class, 200)->create();
        Lara\Person::all()->each(function (Person $person) {
            if(isset($person->prsn_ldap_id)) {
                if($person->club->section() == null){
                    $section = \Lara\Section::query()->inRandomOrder()->first();
                    $club = \Lara\Club::query()->where('clb_title','=',$section->title)->first();
                    $person->club = $club;
                }
                $user = Lara\User::createFromPerson($person);
                if ($user != null) {
                    $parts = explode(" ", $person->prsn_name, 2);
                    $user->givenname = $parts[0];
                    $user->lastname = $parts[1];
                    $user->privacy_accepted = new \DateTime();
                    $user->save();
                }
            }
        });
    }
}
