<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('prsn_name');
                    // must not be empty, needs validation on creation
            $table->string('prsn_ldap_id')->nullable()->default(NULL);                  
                    // filled via LDAP   
            $table->string('prsn_status');
            $table->integer('clb_id')->references('id')->on('clubs')->default('1'); 
                    // default = '1' = "extern"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('persons');
    }
}
