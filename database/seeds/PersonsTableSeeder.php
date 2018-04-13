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
            $user = Lara\User::createFromPerson($person);
            if ($user != NULL) {
                $parts = explode(" ", $person->prsn_name, 2);
                $user->givenname = $parts[0];
                $user->lastname = $parts[1];
                $user->save();
            }
        });
    }
}
