<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Lara\Schedule;
use Lara\ClubEvent;
use Lara\Log;


class AddLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('logs', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->references('id')->on('users')->nullable();

            $table->integer('loggable_id');
            $table->string('loggable_type');

            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();

            $table->string('action');
            $table->string('ip');
            $table->timestamps();
        });

        foreach (ClubEvent::all() as $event) {
            $schedule = $event->schedule;

            $previousLogs = json_decode($schedule->entry_revisions, true);
            if ($previousLogs) {

                foreach($previousLogs as $log) {
                    $person = Lara\Person::where('prsn_ldap_id',  $log['user id'])->first();
                    $user = $log['user id'] && $person ? $person->user : NULL;

                    $isEvent = strpos($log['action'], 'event') !== false; 
                    $isShift = $log['entry id'] != '';

                    Lara\Log::create([
                        'user_id' => $user ? $user->id : NULL,
                        'loggable_id' =>    $isEvent ? $schedule->event->id :
                                            ($isShift ? $log['entry id'] : $schedule->id),
                        'loggable_type' => $isEvent ? 'Lara\ClubEvent':
                                            ($isShift ? 'Lara\Shift' : 'Lara\Schedule'),
                        'action' => $log['action'],
                        'old_value' => $log['old value'],
                        'new_value' => $log['new value'],
                        'ip' => array_key_exists('from ip', $log) ? $log['from ip'] : '',
                        'created_at' => new Carbon\Carbon($log['timestamp']),
                        'updated_at' => new Carbon\Carbon($log['timestamp'])
                    ]);
                }
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
        Schema::dropIfExists('logs');
    }
}
