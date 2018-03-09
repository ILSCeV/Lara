<?php

use Illuminate\Database\Seeder;
use Lara\Role;
use Lara\Section;
use Lara\utilities\RoleUtility;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('role_user')->delete();
        \DB::table('roles')->delete();

        foreach (Section::all() as $section) {
            foreach (RoleUtility::ALL_PRIVILEGES as $roleName) {
                $role = new Role(['name' => $roleName]);
                $role->section_id = $section->id;
                $role->save();
            }
        }
    }
}
