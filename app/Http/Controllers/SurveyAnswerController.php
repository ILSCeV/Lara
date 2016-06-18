<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
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
        $survey = Survey::findOrFail($surveyid);

        //check if survey needs a password and validate hashes
        if ($survey->password !== ''
            && !Hash::check( $input->password, $survey->password ) ) {
            return response()->json("Fehler: das angegebene Passwort ist falsch, keine Änderungen wurden gespeichert. Bitte versuche erneut oder frage ein anderes Mitglied oder CL.", 401);
        }

        $club = Club::where('clb_title', $input->club)->first();

        $survey_answer = new SurveyAnswer();
        $survey_answer->creator_id = Session::get('userId');
        $survey_answer->survey_id = $surveyid;
        $survey_answer->name = $input->name;
        $survey_answer->club_id = $club->id;
        $survey_answer->order = 0;                                  // example, might be better to order bei updated_at?
        $survey_answer->save();

        $questions = $survey->getQuestions;
        foreach($input->answers as $key => $answer_cell) {
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
