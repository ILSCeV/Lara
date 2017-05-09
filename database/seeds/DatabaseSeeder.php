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
        $this->call('PlacesTableSeeder');
        $this->call('ClubsTableSeeder');
        $this->call('JobtypesTableSeeder');
        $this->call('PersonsTableSeeder');
        $this->call('ClubEventsTableSeeder');
        $this->call('SchedulesTableSeeder');
        $this->call('ScheduleEntriesTableSeeder');

        $this->call('SurveysTableSeeder');
        $this->call('SurveyQuestionsTableSeeder');
        $this->call('SurveyAnswersTableSeeder');
        $this->call('SurveyAnswerCellsTableSeeder');
        #iseed_end
    }
}
