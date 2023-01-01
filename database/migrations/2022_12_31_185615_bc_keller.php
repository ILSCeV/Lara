<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lara\Club;
use Lara\Role;
use Lara\Section;
use Lara\User;
use Lara\utilities\RoleUtility;

class BcKeller extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $section = new Section();
        $section->section_uid = hash("sha512", uniqid());
        $section->title = 'bc-Keller';
        $club = new Club();

        $section->color = 'Yellow';
        $section->preparationTime = '20:00:00';
        $section->startTime = '21:00:00';
        $section->endTime = '03:00:00';
        $section->is_name_private = true;
        $section->save();

        RoleUtility::createRolesForNewSection($section);

        $club->clb_title = $section->title;
        $club->save();

        $sectionIds = Section::whereIn('title', [
            'bc-Club',
            'bc-Café',
            'bd-Club'
        ])->get()->map(function (Section $sec) {
            return $sec->id;
        })->toArray();

        /** @var \Illuminate\Database\Eloquent\Collection|User $users */
        $users = User::whereIn('section_id', $sectionIds)->get();

        $users->each(function (User $u) use ($sectionIds, $section) {
            $userRoles = $u->roles()->whereIn('section_id', $sectionIds)->get();
            $userRoles->each(
                function (Role $r) use ($u, $section) {
                    RoleUtility::assignPrivileges($u, $section, $r->name);
                }
            );
        });
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
