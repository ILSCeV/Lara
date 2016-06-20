<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;

use Lara\Survey;
use Lara\SurveyQuestion;
use Lara\SurveyAnswer;
use Lara\SurveyAnswerOption;
use Lara\SurveyAnswerCell;
use Lara\Club;


/**
 * Class SurveyAnswerController
 * @package Lara\Http\Controllers
 * 
 * RESTful Resource Controller, implements only 3 RESTful actions (store, update, destroy)
 */
class SurveyAnswerController extends Controller
{
    /**
     * SurveyAnswerController constructor.
     * call middleware to give only authenticated users access to the methods
     */
    public function __construct()
    {
        // if survey is private, reject guests
        $this->middleware('privateEntry:Lara\Survey,survey');
        // only Ersteller/Admin/Marketing/Clubleitung
        $this->middleware('creator:Lara\SurveyAnswer,answer', ['only' => ['update', 'destroy']]);
        // after deadline, only Ersteller/Admin/Marketing/Clubleitung
        $this->middleware('deadlineSurvey', ['only' => ['update', 'destroy']]);
    }
    
    /**
     * @param int $surveyid
     * @param Request $input
     * @return Redirect;
     */
    public function store($surveyid, Request $input)
    {
//        var_dump($input->answers);
        $survey = Survey::findOrFail($surveyid);
        $club = Club::where('clb_title', $input->club)->first();
        
        $survey_answer = new SurveyAnswer();
        $survey_answer->creator_id = Session::get('userId');
        $survey_answer->survey_id = $surveyid;
        $survey_answer->name = $input->name;
        if(!empty($club)) {
            $survey_answer->club_id = $club->id;
        }
        $survey_answer->order = 0;                                  // example, might be better to order bei updated_at?
        $survey_answer->save();

        $questions = $survey->getQuestions;

        foreach($questions as $key => $question) {
            $survey_answer_cell = new SurveyAnswerCell();
            $survey_answer_cell->survey_question_id = $question->id;
            $survey_answer_cell->survey_answer_id = $survey_answer->id;
            switch($question->field_type) {
                case 1: //Freitext
                    $survey_answer_cell->answer = $input->answers[$key];
                    break;
                case 2: //Checkbox (Ja/Nein)
                    if($input->answers[$key] == -1) {
                        $survey_answer_cell->answer = "keine Angabe";
                    } elseif ($input->answers[$key] == 0) {
                        $survey_answer_cell->answer = "Nein";
                    } elseif ($input->answers[$key] == 1) {
                        $survey_answer_cell->answer = "Ja";
                    }
                    break;
                case 3: //Dropdown
                    if($input->answers[$key] == -1) {
                        $survey_answer_cell->answer = "keine Angabe";
                    } else {
                        $survey_answer_cell->answer = $input->answers[$key];
                    }
                    break;
            }
            $survey_answer_cell->save();
        }
        Session::put('message', 'Erfolgreich abgestimmt!');
        Session::put('msgType', 'success');

        return Redirect::action('SurveyController@show', array('id' => $survey->id));
    }

    /**
     * @param int $surveyid
     * @param int $id
     */
    public function update($surveyid, $id)
    {

    }

    /**
     * @param int $surveyid
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($surveyid, $id)
    {
        $answer = SurveyAnswer::findOrFail($id);

        foreach ($answer->getAnswerCells as $cell) {
            //delete the AnswerCells
            $cell->delete();
        }

        // Now delete the SurveyAnswer itself
        $answer->delete();

        Session::put('message', 'Erfolgreich gelöscht!' );
        Session::put('msgType', 'success');

        return Redirect::action('SurveyController@show', array('id' => $surveyid));
    }
}
