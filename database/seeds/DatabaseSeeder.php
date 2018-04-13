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
        $this->call('SectionsTableSeeder');
        $this->call('SectionPreferencesTableSeeder');
        $this->call('RoleTableSeeder');
        $this->call('ClubsTableSeeder');
        $this->call('ShiftTypesTableSeeder');
        $this->call('PersonsTableSeeder');
        $this->call('ClubEventsTableSeeder');
        $this->call('SchedulesTableSeeder');
        $this->call('ShiftsTableSeeder');
        $this->call('TemplatesTableSeeder');

        $this->call('SurveysTableSeeder');
        $this->call('SurveyQuestionsTableSeeder');
        $this->call('SurveyAnswersTableSeeder');
        $this->call('SurveyAnswerCellsTableSeeder');
        #iseed_end
    }
}
