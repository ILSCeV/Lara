<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Lara\Library\Revision;
use Session;
use Redirect;

use Lara\Survey;
use Lara\SurveyAnswer;
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

        //check if survey needs a password and validate hashes
        if ($survey->password !== ''
            && !Hash::check( $input->password, $survey->password ) ) {
            Session::put('message', 'Fehler: das angegebene Passwort ist falsch, keine Änderungen wurden gespeichert. Bitte versuche es erneut oder frage ein anderes Mitglied oder CL.');
            Session::put('msgType', 'danger');
            return Redirect::action('SurveyController@show', array('id' => $survey->id));
        }

        $club = Club::where('clb_title', $input->club)->first();
        
        $survey_answer = new SurveyAnswer();
        $revision_answer = new Revision($survey_answer);
        $survey_answer->creator_id = Session::get('userId');
        $survey_answer->survey_id = $surveyid;
        $survey_answer->name = $input->name;
        if(!empty($club)) {
            $survey_answer->club_id = $club->id;
        }
        $survey_answer->order = 0; // example, might be better to order bei updated_at?
        $survey_answer->save();
        $revision_answer->save($survey_answer, "Antwort gespeichert");

        $questions = $survey->getQuestions;

        foreach($questions as $key => $question) {
            $survey_answer_cell = new SurveyAnswerCell();
            $revision_cell = new Revision($survey_answer_cell);
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
            $revision_cell->save($survey_answer_cell, "Teilantwort gespeichert");
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
        $revision_answer = new Revision($answer);

        foreach ($answer->getAnswerCells as $cell) {
            //delete the AnswerCells
            $revision_cell = new Revision($cell);
            $cell->delete();
            $revision_cell->save($cell, "Teilantwort gelöscht");
        }

        // Now delete the SurveyAnswer itself
        $answer->delete();
        $revision_answer->save($answer, "Antwort gelöscht");

        Session::put('message', 'Erfolgreich gelöscht!' );
        Session::put('msgType', 'success');

        return Redirect::action('SurveyController@show', array('id' => $surveyid));
    }
}
