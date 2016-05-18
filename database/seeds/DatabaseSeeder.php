<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Tables will be cleared and filled with example entries.
     *
     * @return void
     */
    public function run() {
        Eloquent::unguard();

        #iseed_start
        // do not remove
        // iSeed will store the calls for newly generated seeds here
        $this->call('ClubEventsTableSeeder');
        $this->call('SchedulesTableSeeder');
        $this->call('PersonsTableSeeder');
        $this->call('JobtypesTableSeeder');
        $this->call('PlacesTableSeeder');
        $this->call('ClubsTableSeeder');
        $this->call('ScheduleEntriesTableSeeder');
        $this->call('SurveyTableSeeder');
        $this->call('SurveyQuestionTableSeeder');
        $this->call('SurveyAnswerTableSeeder');
        #iseed_end
    }
}
