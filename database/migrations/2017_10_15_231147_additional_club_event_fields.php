<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdditionalClubEventFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('club_events', function (Blueprint $table) {
            $table->boolean("facebook_done")->nullable();
            $table->mediumText("event_url")->nullable();
            $table->float("price_tickets_normal")->nullable();
            $table->float("price_tickets_external")->nullable();
            $table->float("price_normal")->nullable();
            $table->float("price_external")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('club_events', function (Blueprint $table) {
            $table->dropColumn('facebook_done');
            $table->dropColumn('event_url');

            $table->dropColumn('price_tickets_normal');
            $table->dropColumn('price_tickets_external');
            $table->dropColumn('price_normal');
            $table->dropColumn('price_external');
        });
    }
}
