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
            $table->boolean("facebook_done")->default(false);
            $table->mediumText("facebook_event_url")->default("");
            $table->float("price_tickets_normal");
            $table->float("price_tickets_external");
            $table->float("price_normal");
            $table->float("price_external");
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
            $table->dropColumn('facebook_event_url');
    
            $table->dropColumn('price_tickets_normal');
            $table->dropColumn('price_tickets_external');
            $table->dropColumn('price_normal');
            $table->dropColumn('price_external');
        });
    }
}
