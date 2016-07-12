<?php

use Illuminate\Database\Seeder;

class MigrationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('migrations')->delete();
        
        \DB::table('migrations')->insert(array (
            0 => 
            array (
                'migration' => '2015_11_05_182756_create_schedule_entries_table',
                'batch' => 1,
            ),
            1 => 
            array (
                'migration' => '2015_11_05_183714_create_schedules_table',
                'batch' => 1,
            ),
            2 => 
            array (
                'migration' => '2015_11_05_183944_create_club_events_table',
                'batch' => 1,
            ),
            3 => 
            array (
                'migration' => '2015_11_05_184347_create_clubs_table',
                'batch' => 1,
            ),
            4 => 
            array (
                'migration' => '2015_11_05_184522_create_places_table',
                'batch' => 1,
            ),
            5 => 
            array (
                'migration' => '2015_11_05_184646_create_persons_table',
                'batch' => 1,
            ),
            6 => 
            array (
                'migration' => '2015_11_05_190445_create_jobtypes_table',
                'batch' => 1,
            ),
            7 => 
            array (
                'migration' => '2016_04_24_141145_add-showTo-filter',
                'batch' => 1,
            ),
            8 => 
            array (
                'migration' => '2016_04_30_043959_create_surveys_table',
                'batch' => 1,
            ),
            9 => 
            array (
                'migration' => '2016_04_30_044127_create_survey_questions_table',
                'batch' => 1,
            ),
            10 => 
            array (
                'migration' => '2016_04_30_044140_create_survey_answers_table',
                'batch' => 1,
            ),
            11 => 
            array (
                'migration' => '2016_05_04_134614_remove_obsolete_schedule_columns',
                'batch' => 1,
            ),
            12 => 
            array (
                'migration' => '2016_05_24_004251_create_survey_answer_cells_table',
                'batch' => 1,
            ),
            13 => 
            array (
                'migration' => '2016_05_25_231442_create_survey_answer_options_table',
                'batch' => 1,
            ),
            14 => 
            array (
                'migration' => '2016_06_23_192245_create_revisions',
                'batch' => 1,
            ),
            15 => 
            array (
                'migration' => '2016_06_23_192255_create_revision_entries',
                'batch' => 1,
            ),
            16 => 
            array (
                'migration' => '2016_07_04_142611_create_revision_object_relations',
                'batch' => 1,
            ),
        ));
        
        
    }
}
