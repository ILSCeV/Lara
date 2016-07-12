<?php

use Illuminate\Database\Seeder;

class RevisionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('revisions')->delete();
        
        \DB::table('revisions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'creator_id' => 1011,
                'summary' => 'Umfrage erstellt',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey',
                'created_at' => '2016-07-12 13:07:41',
                'updated_at' => '2016-07-12 13:07:41',
            ),
            1 => 
            array (
                'id' => 2,
                'creator_id' => 1011,
                'summary' => 'Antwort erstellt',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey/1/answer',
                'created_at' => '2016-07-12 13:08:00',
                'updated_at' => '2016-07-12 13:08:00',
            ),
            2 => 
            array (
                'id' => 3,
                'creator_id' => 1011,
                'summary' => 'Antwort erstellt',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey/1/answer',
                'created_at' => '2016-07-12 13:08:23',
                'updated_at' => '2016-07-12 13:08:23',
            ),
            3 => 
            array (
                'id' => 4,
                'creator_id' => 1011,
                'summary' => 'Umfrage erstellt',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey',
                'created_at' => '2016-07-12 15:25:08',
                'updated_at' => '2016-07-12 15:25:08',
            ),
            4 => 
            array (
                'id' => 5,
                'creator_id' => 1011,
                'summary' => 'Umfrage erstellt',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey',
                'created_at' => '2016-07-12 15:29:32',
                'updated_at' => '2016-07-12 15:29:32',
            ),
            5 => 
            array (
                'id' => 6,
                'creator_id' => 1011,
                'summary' => 'Antwort erstellt',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey/3/answer',
                'created_at' => '2016-07-12 15:30:12',
                'updated_at' => '2016-07-12 15:30:12',
            ),
            6 => 
            array (
                'id' => 7,
                'creator_id' => 1011,
                'summary' => 'Umfrage erstellt',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey',
                'created_at' => '2016-07-12 15:34:16',
                'updated_at' => '2016-07-12 15:34:16',
            ),
            7 => 
            array (
                'id' => 8,
                'creator_id' => 1011,
                'summary' => 'Antwort erstellt',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey/4/answer',
                'created_at' => '2016-07-12 15:34:42',
                'updated_at' => '2016-07-12 15:34:42',
            ),
            8 => 
            array (
                'id' => 9,
                'creator_id' => 1011,
                'summary' => 'Antwort erstellt',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey/4/answer',
                'created_at' => '2016-07-12 15:35:08',
                'updated_at' => '2016-07-12 15:35:08',
            ),
            9 => 
            array (
                'id' => 10,
                'creator_id' => 1011,
                'summary' => 'Antwort erstellt',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey/4/answer',
                'created_at' => '2016-07-12 15:35:32',
                'updated_at' => '2016-07-12 15:35:32',
            ),
            10 => 
            array (
                'id' => 11,
                'creator_id' => 1011,
                'summary' => 'Antwort erstellt',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey/4/answer',
                'created_at' => '2016-07-12 15:36:04',
                'updated_at' => '2016-07-12 15:36:04',
            ),
            11 => 
            array (
                'id' => 12,
                'creator_id' => 1011,
                'summary' => 'Umfrage erstellt',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey',
                'created_at' => '2016-07-12 18:28:29',
                'updated_at' => '2016-07-12 18:28:29',
            ),
            12 => 
            array (
                'id' => 13,
                'creator_id' => 1011,
                'summary' => 'Umfrage geändert',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey/5',
                'created_at' => '2016-07-12 18:28:43',
                'updated_at' => '2016-07-12 18:28:43',
            ),
            13 => 
            array (
                'id' => 14,
                'creator_id' => 1011,
                'summary' => 'Umfrage erstellt',
                'ip' => '10.0.2.2',
                'request_uri' => 'http://localhost:8000/survey',
                'created_at' => '2016-07-12 18:30:36',
                'updated_at' => '2016-07-12 18:30:36',
            ),
        ));
        
        
    }
}
