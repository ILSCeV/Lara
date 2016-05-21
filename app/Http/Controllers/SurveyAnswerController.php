<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;

use Lara\Http\Requests;
use Lara\Survey;
use Lara\SurveyAnswer;

class SurveyAnswerController extends Controller
{
    /*
     * testing purposes only
     */
    public function show($surveyid, $id)
    {
        $input = ['name' => 'Hans',
                  'answer' => [0 => 'testantwort 1', 1 => 'testantwort 2']
                 ];
        var_dump($surveyid, $id);
        $survey = Survey::findOrFail($surveyid);
        foreach($input['answer'] as $answer) {
            var_dump($answer);
            $survey_answer = new SurveyAnswer();
        }
    }
    /**
     * @param int $surveyid
     * @param Request $input
     */
    public function store($surveyid, Request $input)
    {
        var_dump($input->all());
    }

    public function update($id)
    {

    }

    public function destroy()
    {

    }
}
