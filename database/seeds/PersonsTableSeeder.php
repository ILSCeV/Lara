<?php

use Illuminate\Database\Seeder;
use Lara\Person;
use Lara\Section;
use Lara\utilities\RoleUtility;

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
        $roles = collect(RoleUtility::ALL_PRIVILEGES);
        $sections = Section::all();
        $faker = Faker\Factory::create('de_DE');
        
        factory(Lara\Person::class, 200)->create();
        DB::transaction(function () use ($roles, $sections, $faker) {
            Lara\Person::query()->whereNotNull('prsn_ldap_id')->get()->each(function (Person $person) use (
                $roles,
                $sections,
                $faker
            ) {
                $user = Lara\User::createFromPerson($person);
                if ($user != null) {
                    $onLeave = $faker->boolean(20) ? $faker->dateTimeBetween('-5 months', '+6 months') : null;
                    $parts = explode(" ", $person->prsn_name, 2);
                    $user->givenname = $parts[0];
                    $user->lastname = $parts[1];
                    $user->privacy_accepted = new \DateTime();
                    $user->on_leave = $onLeave;
                    $user->email = $faker->unique()->safeEmail;
                    $user->save();
                    RoleUtility::assignPrivileges($user, $sections->random(),
                        $roles->random());
                }
            });
        });
    }
}
