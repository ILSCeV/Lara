<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Lara\Library\Revision;
use Session;
use Redirect;
use Input;

use Lara\Survey;
use Lara\SurveyAnswer;
use Lara\SurveyAnswerCell;
use Lara\Http\Requests;
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
        // only Ersteller/Admin/Marketing/Clubleitung, privileged user groups only
        $this->middleware('creator:Lara\SurveyAnswer,answer', ['only' => ['update', 'destroy']]);
        // after deadline, only Ersteller/Admin/Marketing/Clubleitung, privileged user groups only
        $this->middleware('deadlineSurvey', ['only' => ['store', 'update', 'destroy']]);
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
            Session::put('message', 'Fehler: das angegebene Passwort ist falsch, keine Änderungen wurden gespeichert. Bitte versuche es erneut oder frage ein anderes Mitglied oder CL.');
            Session::put('msgType', 'danger');
            return Redirect::action('SurveyController@show', array('id' => $survey->id));
        }
        
        $survey_answer = new SurveyAnswer();
		$revision_answer = new Revision($survey_answer);
        // prevent guestentries with ldapId
        // prevent entries with foreign usernames but valid ldap_id
        if(Session::has('userId') AND Session::get('userName')  == $input->name) {
            $survey_answer->creator_id = $input->ldapId;
        } else {
            $survey_answer->creator_id = null;
        }

        $survey_answer->survey_id = $surveyid;
        $survey_answer->name = $input->name;
        $survey_answer->club = $input->club;
        $survey_answer->order = 0; // example, might be better to order by updated_at?
        $survey_answer->save();
        $revision_answer->save($survey_answer, "Antwort");

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
            $revision_cell->save($survey_answer_cell);
        }
        Session::put('message', 'Erfolgreich abgestimmt!');
        Session::put('msgType', 'success');

        return Redirect::action('SurveyController@show', array('id' => $survey->id));
    }

    /**
     * @param int $surveyid
     * @param int $answerid
     * @param Request $input
     * @return json
     */
    public function update($surveyid, $answerid, Request $input)
    {
        //validate session token
        if (Session::token() !== $input->get('_token')) {
            return response()->json('Fehler: die Session ist abgelaufen. Bitte aktualisiere die Seite und logge dich ggf. erneut ein.', 401);
        }

        $survey = Survey::findOrFail($surveyid);

        //check if survey needs a password and validate hashes
        if ($survey->password !== ''
            && !Hash::check( $input->password, $survey->password ) ) {
            return response()->json('Fehler: das eingegebene Passwort war leider falsch.', 401);
        }

        //give a reminder to fill out required fields
        if ($input->error[0] == "required_missing") {
            return response()->json('Fehler: Es wurden nicht alle Pflichtfragen beantwortet.', 401);
        }

        $survey_answer = SurveyAnswer::findOrFail($answerid);
        $revision_answer = new Revision($survey_answer);
        // prevent guestentries with ldapId
        (Session::has('userId')) ? ($survey_answer->creator_id = $input->ldapId) : ($survey_answer->creator_id = null);
        $survey_answer->survey_id = $surveyid;
        $survey_answer->name = $input->name;
        $survey_answer->club = $input->club;
        $survey_answer->order = 0; // example, might be better to order by updated_at?
        $survey_answer->save();
        $revision_answer->save($survey_answer);

        $questions = $survey->getQuestions;
        $answer_cells = $survey_answer->getAnswerCells;

        foreach($questions as $key => $question) {
            $survey_answer_cell = SurveyAnswerCell::find($answer_cells->get($key)->id);
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
            $revision_cell->save($survey_answer_cell);
        }

        $creator = $survey_answer->getPerson;
        (!empty($creator)) ? ($user_status = $creator->prsn_status) : ($user_status = null);

        $user_status = $this->updateStatus($user_status);
        return response()->json(["user_status" => $user_status
        ],
            200);
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
            $revision_cell->save($cell);
        }

        // Now delete the SurveyAnswer itself
        $answer->delete();
        $revision_answer->save($answer, "Antwort");

        Session::put('message', 'Erfolgreich gelöscht!' );
        Session::put('msgType', 'success');

        return Redirect::action('SurveyController@show', array('id' => $surveyid));
    }

    /*
     *  -------------------- END OF REST-CONTROLLER --------------------
     */


    /**
     * returns the the style (colored dot) for the corresponding status
     * used in ajax requests
     * @param string $user_status
     * @return array
     */
    private function updateStatus($user_status)
    {
        switch ($user_status) {
            case 'candidate':
                $user_status_style = ["status" => "fa fa-adjust", "style" => "color:yellowgreen;", "title" => "Kandidat"];
                break;
            case 'veteran':
                $user_status_style = ["status" => "fa fa-star", "style" => "color:gold;", "title" => "Veteran"];
                break;
            case 'member':
                $user_status_style = ["status" => "fa fa-circle", "style" => "color:forestgreen;", "title" => "Aktiv"];
                break;
            case 'resigned':
                $user_status_style = ["status" => "fa fa-star-o", "style" => "color:gold;", "title" => "ex-Mitglied"];
                break;
            default:
                $user_status_style = ["status" => "fa fa-circle", "style" => "color:lightgrey;", "title" => "Extern"];
        }
        return $user_status_style;
    }
}

