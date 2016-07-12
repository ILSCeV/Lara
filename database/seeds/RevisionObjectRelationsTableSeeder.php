<?php

use Illuminate\Database\Seeder;

class RevisionObjectRelationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('revision_object_relations')->delete();
        
        \DB::table('revision_object_relations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'revision_id' => 1,
                'object_id' => 1,
                'object_name' => 'Survey',
            ),
            1 => 
            array (
                'id' => 2,
                'revision_id' => 1,
                'object_id' => 1,
                'object_name' => 'SurveyQuestion',
            ),
            2 => 
            array (
                'id' => 3,
                'revision_id' => 1,
                'object_id' => 2,
                'object_name' => 'SurveyQuestion',
            ),
            3 => 
            array (
                'id' => 4,
                'revision_id' => 1,
                'object_id' => 3,
                'object_name' => 'SurveyQuestion',
            ),
            4 => 
            array (
                'id' => 5,
                'revision_id' => 2,
                'object_id' => 1,
                'object_name' => 'SurveyAnswer',
            ),
            5 => 
            array (
                'id' => 6,
                'revision_id' => 2,
                'object_id' => 1,
                'object_name' => 'SurveyAnswerCell',
            ),
            6 => 
            array (
                'id' => 7,
                'revision_id' => 2,
                'object_id' => 2,
                'object_name' => 'SurveyAnswerCell',
            ),
            7 => 
            array (
                'id' => 8,
                'revision_id' => 2,
                'object_id' => 3,
                'object_name' => 'SurveyAnswerCell',
            ),
            8 => 
            array (
                'id' => 9,
                'revision_id' => 3,
                'object_id' => 2,
                'object_name' => 'SurveyAnswer',
            ),
            9 => 
            array (
                'id' => 10,
                'revision_id' => 3,
                'object_id' => 4,
                'object_name' => 'SurveyAnswerCell',
            ),
            10 => 
            array (
                'id' => 11,
                'revision_id' => 3,
                'object_id' => 5,
                'object_name' => 'SurveyAnswerCell',
            ),
            11 => 
            array (
                'id' => 12,
                'revision_id' => 3,
                'object_id' => 6,
                'object_name' => 'SurveyAnswerCell',
            ),
            12 => 
            array (
                'id' => 13,
                'revision_id' => 4,
                'object_id' => 2,
                'object_name' => 'Survey',
            ),
            13 => 
            array (
                'id' => 14,
                'revision_id' => 4,
                'object_id' => 4,
                'object_name' => 'SurveyQuestion',
            ),
            14 => 
            array (
                'id' => 15,
                'revision_id' => 5,
                'object_id' => 3,
                'object_name' => 'Survey',
            ),
            15 => 
            array (
                'id' => 16,
                'revision_id' => 5,
                'object_id' => 5,
                'object_name' => 'SurveyQuestion',
            ),
            16 => 
            array (
                'id' => 17,
                'revision_id' => 5,
                'object_id' => 6,
                'object_name' => 'SurveyQuestion',
            ),
            17 => 
            array (
                'id' => 18,
                'revision_id' => 5,
                'object_id' => 7,
                'object_name' => 'SurveyQuestion',
            ),
            18 => 
            array (
                'id' => 19,
                'revision_id' => 5,
                'object_id' => 8,
                'object_name' => 'SurveyQuestion',
            ),
            19 => 
            array (
                'id' => 20,
                'revision_id' => 5,
                'object_id' => 9,
                'object_name' => 'SurveyQuestion',
            ),
            20 => 
            array (
                'id' => 21,
                'revision_id' => 5,
                'object_id' => 10,
                'object_name' => 'SurveyQuestion',
            ),
            21 => 
            array (
                'id' => 22,
                'revision_id' => 5,
                'object_id' => 11,
                'object_name' => 'SurveyQuestion',
            ),
            22 => 
            array (
                'id' => 23,
                'revision_id' => 6,
                'object_id' => 3,
                'object_name' => 'SurveyAnswer',
            ),
            23 => 
            array (
                'id' => 24,
                'revision_id' => 6,
                'object_id' => 7,
                'object_name' => 'SurveyAnswerCell',
            ),
            24 => 
            array (
                'id' => 25,
                'revision_id' => 6,
                'object_id' => 8,
                'object_name' => 'SurveyAnswerCell',
            ),
            25 => 
            array (
                'id' => 26,
                'revision_id' => 6,
                'object_id' => 9,
                'object_name' => 'SurveyAnswerCell',
            ),
            26 => 
            array (
                'id' => 27,
                'revision_id' => 6,
                'object_id' => 10,
                'object_name' => 'SurveyAnswerCell',
            ),
            27 => 
            array (
                'id' => 28,
                'revision_id' => 6,
                'object_id' => 11,
                'object_name' => 'SurveyAnswerCell',
            ),
            28 => 
            array (
                'id' => 29,
                'revision_id' => 6,
                'object_id' => 12,
                'object_name' => 'SurveyAnswerCell',
            ),
            29 => 
            array (
                'id' => 30,
                'revision_id' => 6,
                'object_id' => 13,
                'object_name' => 'SurveyAnswerCell',
            ),
            30 => 
            array (
                'id' => 31,
                'revision_id' => 7,
                'object_id' => 4,
                'object_name' => 'Survey',
            ),
            31 => 
            array (
                'id' => 32,
                'revision_id' => 7,
                'object_id' => 12,
                'object_name' => 'SurveyQuestion',
            ),
            32 => 
            array (
                'id' => 33,
                'revision_id' => 7,
                'object_id' => 13,
                'object_name' => 'SurveyQuestion',
            ),
            33 => 
            array (
                'id' => 34,
                'revision_id' => 7,
                'object_id' => 14,
                'object_name' => 'SurveyQuestion',
            ),
            34 => 
            array (
                'id' => 35,
                'revision_id' => 8,
                'object_id' => 4,
                'object_name' => 'SurveyAnswer',
            ),
            35 => 
            array (
                'id' => 36,
                'revision_id' => 8,
                'object_id' => 14,
                'object_name' => 'SurveyAnswerCell',
            ),
            36 => 
            array (
                'id' => 37,
                'revision_id' => 8,
                'object_id' => 15,
                'object_name' => 'SurveyAnswerCell',
            ),
            37 => 
            array (
                'id' => 38,
                'revision_id' => 8,
                'object_id' => 16,
                'object_name' => 'SurveyAnswerCell',
            ),
            38 => 
            array (
                'id' => 39,
                'revision_id' => 9,
                'object_id' => 5,
                'object_name' => 'SurveyAnswer',
            ),
            39 => 
            array (
                'id' => 40,
                'revision_id' => 9,
                'object_id' => 17,
                'object_name' => 'SurveyAnswerCell',
            ),
            40 => 
            array (
                'id' => 41,
                'revision_id' => 9,
                'object_id' => 18,
                'object_name' => 'SurveyAnswerCell',
            ),
            41 => 
            array (
                'id' => 42,
                'revision_id' => 9,
                'object_id' => 19,
                'object_name' => 'SurveyAnswerCell',
            ),
            42 => 
            array (
                'id' => 43,
                'revision_id' => 10,
                'object_id' => 6,
                'object_name' => 'SurveyAnswer',
            ),
            43 => 
            array (
                'id' => 44,
                'revision_id' => 10,
                'object_id' => 20,
                'object_name' => 'SurveyAnswerCell',
            ),
            44 => 
            array (
                'id' => 45,
                'revision_id' => 10,
                'object_id' => 21,
                'object_name' => 'SurveyAnswerCell',
            ),
            45 => 
            array (
                'id' => 46,
                'revision_id' => 10,
                'object_id' => 22,
                'object_name' => 'SurveyAnswerCell',
            ),
            46 => 
            array (
                'id' => 47,
                'revision_id' => 11,
                'object_id' => 7,
                'object_name' => 'SurveyAnswer',
            ),
            47 => 
            array (
                'id' => 48,
                'revision_id' => 11,
                'object_id' => 23,
                'object_name' => 'SurveyAnswerCell',
            ),
            48 => 
            array (
                'id' => 49,
                'revision_id' => 11,
                'object_id' => 24,
                'object_name' => 'SurveyAnswerCell',
            ),
            49 => 
            array (
                'id' => 50,
                'revision_id' => 11,
                'object_id' => 25,
                'object_name' => 'SurveyAnswerCell',
            ),
            50 => 
            array (
                'id' => 51,
                'revision_id' => 12,
                'object_id' => 5,
                'object_name' => 'Survey',
            ),
            51 => 
            array (
                'id' => 52,
                'revision_id' => 12,
                'object_id' => 15,
                'object_name' => 'SurveyQuestion',
            ),
            52 => 
            array (
                'id' => 53,
                'revision_id' => 13,
                'object_id' => 5,
                'object_name' => 'Survey',
            ),
            53 => 
            array (
                'id' => 54,
                'revision_id' => 13,
                'object_id' => 15,
                'object_name' => 'SurveyQuestion',
            ),
            54 => 
            array (
                'id' => 55,
                'revision_id' => 14,
                'object_id' => 6,
                'object_name' => 'Survey',
            ),
            55 => 
            array (
                'id' => 56,
                'revision_id' => 14,
                'object_id' => 16,
                'object_name' => 'SurveyQuestion',
            ),
            56 => 
            array (
                'id' => 57,
                'revision_id' => 14,
                'object_id' => 17,
                'object_name' => 'SurveyQuestion',
            ),
            57 => 
            array (
                'id' => 58,
                'revision_id' => 14,
                'object_id' => 18,
                'object_name' => 'SurveyQuestion',
            ),
            58 => 
            array (
                'id' => 59,
                'revision_id' => 14,
                'object_id' => 19,
                'object_name' => 'SurveyQuestion',
            ),
        ));
        
        
    }
}
