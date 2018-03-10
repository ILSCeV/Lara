<?php

use Illuminate\Database\Seeder;

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
        Lara\Person::all()->each(function($person) {
            Lara\User::createFromPerson($person);
        });
    }
}
