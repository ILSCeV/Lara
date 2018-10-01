<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendNameIsPrivateAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('sections', function (Blueprint $table) {
            $table->boolean('is_name_private')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_name_private')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn('is_name_private');
        });
    
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_name_private');
        });
    }
}
