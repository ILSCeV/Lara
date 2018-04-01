<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Lara\Role;
use Lara\Section;
use Lara\utilities\RoleUtility;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('section_id')->unsigned()->references('id')->on('sections');
            $table->timestamps();
        });

        foreach (Section::all() as $section) {
            foreach (RoleUtility::ALL_PRIVILEGES as $roleName) {
                $role = new Role(['name' => $roleName]);
                $role->section_id = $section->id;
                $role->save();
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
        Schema::dropIfExists('roles');
    }
}
