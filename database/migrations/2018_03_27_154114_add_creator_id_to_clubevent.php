<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Lara\ClubEvent;
use Lara\Person;
use Lara\User;

class AddCreatorIdToClubevent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('club_events', function(Blueprint $table) {
            $table->integer('creator_id')->references('id')->on('users');
        });

        ClubEvent::all()
            ->each(function(ClubEvent $event) {
                $revisions = json_decode($event->getSchedule->entry_revisions, true);

                if (is_null($revisions)) {
                    return;
                }
                $revisions = array_reverse($revisions);
                $ldap_id = collect($revisions)->last()["user id"];

                if (is_null($ldap_id)) {
                    return;
                }

                $person = Person::where('prsn_ldap_id', $ldap_id)->first();
                if (is_null($person)) {
                    return;
                }
                $user = $person->user;

                $event->creator_id = $user->id;
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('club_events', function(Blueprint $table) {
            $table->dropColumn('creator_id');
        });
    }
}
