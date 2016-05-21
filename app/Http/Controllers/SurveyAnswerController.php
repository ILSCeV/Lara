<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;

use Lara\Http\Requests;
use Lara\Survey;
use Lara\SurveyAnswer;

use Session;
use Redirect;

class SurveyAnswerController extends Controller
{
    /*
     * testing purposes only
     */
    public function show($surveyid, $id)
    {

    }
    /**
     * @param int $surveyid
     * @param Request $input
     * @return Redirect;
     */
    public function store($surveyid, Request $input)
    {
        $survey = Survey::findOrFail($surveyid);
        $questions = $survey->getQuestions;

        foreach($input->answer as $key => $answer) {
            $survey_answer = new SurveyAnswer();
            $survey_answer->survey_question_id = $questions[$key]->id;
            $survey_answer->prsn_id = Session::get('userId');
            $survey_answer->name = "Peter"; // example due to missing Views
//            $survey_answer->name = $input->name; // redundant to save it for every answer -> look for better database concept
            $survey_answer->content = $answer;
            $survey_answer->save();
        }

        Session::put('message', 'Erfolgreich abgestimmt!' );
        Session::put('msgType', 'success');

        return Redirect::action('SurveyController@show', array('id' => $survey->id));
    }

    public function update($id)
    {

    }

    public function destroy()
    {

    }
}
