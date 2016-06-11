<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;

use Lara\Http\Requests;
use Lara\Survey;
use Lara\SurveyAnswer;
use Lara\Person;
use Lara\SurveyAnswerCell;

use Session;
use Redirect;

class SurveyAnswerController extends Controller
{
    public function __construct()
    {
        // if survey is private, reject guests
        $this->middleware('privateEntry:Lara\Survey,survey');
        // only Ersteller/Admin/Marketing/Clubleitung
        $this->middleware('creator:Lara\SurveyAnswer,answer', ['only' => ['update', 'destroy']]);
        // after deadline, only Ersteller/Admin/Marketing/Clubleitung
        $this->middleware('deadlineSurvey', ['only' => ['update', 'destroy']]);
    }

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
//        $creator = Person::findOrFail(Session::get('userId'));    //not able due to no corresponding persons and clubs in database

        $survey_answer = new SurveyAnswer();
        $survey_answer->creator_id = Session::get('userId');
        $survey_answer->survey_id = $surveyid;
        $survey_answer->name = "Peter";                             // example due to missing Views
        $survey_answer->club_id = 0;                                // example
//        $survey_answer->club_id = $creator->getClub->id;          // not able due to no corresponding persons and clubs in database
        $survey_answer->order = 0;                                  // example, might be better to order bei updated_at?
        $survey_answer->save();

        $questions = $survey->getQuestions;
        foreach ($input->answer as $key => $answer_cell) {
            $survey_answer_cell = new SurveyAnswerCell();
            $survey_answer_cell->survey_question_id = $questions[$key]->id;
            $survey_answer_cell->survey_answer_id = $survey_answer->id;
            $survey_answer_cell->answer = $answer_cell;
            $survey_answer_cell->save();
        }

        Session::put('message', 'Erfolgreich abgestimmt!');
        Session::put('msgType', 'success');

        return Redirect::action('SurveyController@show', array('id' => $survey->id));
    }

    public function update($surveyid, $id)
    {

    }

    public function destroy($surveyid, $id)
    {
        $answer = SurveyAnswer::FindOrFail($id);

        foreach ($answer->getAnswerCells as $cell) {
            //delete the AnswerCells
            $cell->delete();
        }

        // Now delete the SurveyAnswer itself
        $answer->delete();

        return Redirect::action('SurveyController@show', array('id' => $surveyid->id));
    }
}
