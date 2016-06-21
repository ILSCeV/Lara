<?php

namespace Lara\Http\Controllers;

use Config;
use Illuminate\Http\Request;
use Hash;
use Session;
use Redirect;
use DateTime;

use Lara\Survey;
use Lara\SurveyQuestion;
use Lara\SurveyAnswer;
use Lara\SurveyAnswerOption;
use Lara\Club;


/**
 * Class SurveyController
 * @package Lara\Http\Controllers
 *
 * RESTful Resource Controller, implements all RESTful actions (index, create, store, show, edit, update, destroy)
 */
class SurveyController extends Controller
{
    /**
     * SurveyController constructor.
     * call middleware to give only authenticated users access to the methods
     */
    public function __construct()
    {
        // reject guests
        $this->middleware('rejectGuests', ['only' => 'create', 'store']);
        // if survey is private, reject guests
        $this->middleware('privateEntry:Lara\Survey,survey', ['except' => ['create', 'store']]);
        // only Ersteller/Admin/Marketing/Clubleitung
        $this->middleware('creator:Lara\Survey,survey', ['only' => ['edit', 'update', 'destroy']]);
        // after deadline, only Ersteller/Admin/Marketing/Clubleitung
        $this->middleware('deadlineSurvey', ['only' => ['edit', 'update', 'destroy']]);
    }

    public function index()
    {

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //prepare correct date and time format to be used in forms for deadline
        $time = new DateTime();
        $time->modify('+14 days');
        $time = $time->format('d-m-Y H:i:s');

        //placeholder because createSurveyView needs variable, can set defaults here
        $survey = new Survey();
        
        return view('createSurveyView', compact('survey','time'));
    }

    /**
     * @param Request $input
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $input)
    {
        if ($input->password != $input->passwordDouble) {
            Session::put('message', Config::get('messages_de.password-mismatch') );
            Session::put('msgType', 'danger');
            return Redirect::back()->withInput();
        }

        $survey = new Survey;
        $survey->creator_id = Session::get('userId');
        $survey->title = $input->title;
        //if URL is in the description, convert it to clickable hyperlink
        $survey->description = $this->addLinks($input->description);
        $survey->deadline = strftime("%Y-%m-%d %H:%M:%S", strtotime($input->deadline));
        $survey->is_anonymous = isset($input->is_anonymous);
        $survey->is_private = isset($input->is_private);
        $survey->show_results_after_voting = isset($input->show_results_after_voting);
        
        if (!empty($input->password)
            AND !empty($input->passwordDouble)
            AND $input->password == $input->passwordDouble) {
            $survey->password = Hash::make($input->password);
        }

        $survey->save();

        $questions = $input->questions;
        $answer_options = $input->answer_options;

        /*
         * get question type as array
         * type = 0: no type given from user, delete question
         * 1: text field
         * 2: checkbox
         * 3: dropdown, has answer options!
         */
        $questions_type = $input->type;
        $required = $input->required;

        //abort if all questions are empty
        if(array_unique($questions) === array('')){
            Session::put('message', 'Es konnten keine Fragen gespeichert werden, 
                                     weil alle Fragen leer gelassen wurden!');
            Session::put('msgType', 'danger');

            return Redirect::back()->withInput();
        }

        //abort if no single field type is given
        if(empty($questions_type) and array_unique($questions_type) === array('0')){
            Session::put('message', 'Es konnten keine Fragen gespeichert werden, 
                                     weil kein einziger Frage-Typ angegeben wurde!');
            Session::put('msgType', 'danger');

            return Redirect::back()->withInput();
        }

        //actual bug: answer options array is too long, must delete unnecessary elements
        for($i = count($answer_options)+1; $i >= count($questions); $i--) {
            unset($answer_options[$i]);
        }

        /*
         * array for the answer options doesn't have an entry for questions without answer options
         * better we fill missing keys, so we can iterate through it later
         * same problem with required array
         */

        for($i = 0; $i < count($questions); $i++) {
            if(array_key_exists($i, $answer_options) === False) {
                $answer_options_new[$i] = '';
            }
            if(empty($required) or array_key_exists($i, $required) === False) {
                $required[$i] = null;
            }
        }
        ksort($answer_options);
        ksort($required);

        //ignore empty questions
        for($i = 0; $i < count($questions); $i++) {

            //check if single empty questions exist or no question type is given
            if (empty($questions[$i]) or $questions_type[$i] == 0) {

                //ignore question
                unset($questions[$i]);
                unset($questions_type[$i]);
                unset($answer_options[$i]);
                unset($required[$i]);

                //reindex arrays
                $questions = array_values($questions);
                $questions_type = array_values($questions_type);
                $answer_options = array_values($answer_options);
                $required = array_values($required);
            }
        }
        
        //ignore empty answer options
        for($i = 0; $i < count($answer_options); $i++) {

            //check if dropdown question and answer options exist
            if($questions_type[$i] == 3 and is_array($answer_options[$i])) {
                //filter empty answer options and reindex
                $answer_options[$i] = array_values(array_filter($answer_options[$i]));
            }
        }
        
        //make new question model instance, fill it and save it
        foreach($questions as $order => $question){
            $question_db = new SurveyQuestion();
            $question_db->survey_id = $survey->id;
            $question_db->order = $order;
            $question_db->field_type = $questions_type[$order];
            $question_db->is_required = (bool) $required[$order];
            $question_db->question = $question;
            $question_db->save();

            //check if question is dropdown question
             if($questions_type[$order] == 3) {
                 foreach($answer_options[$order] as $answer_option) {
                     $answer_option_db = new SurveyAnswerOption();
                     $answer_option_db->survey_question_id = $question_db->id;
                     $answer_option_db->answer_option = $answer_option;
                     $answer_option_db->save();
                 }
             }
        }

        return Redirect::action('SurveyController@show', array('id' => $survey->id));
    }


    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        //find SurveyID
        $survey = Survey::findorFail($id);

        //find answers and questions that belong to SurveyID
        $questions = $survey->getQuestions;
        $answers = $survey->getAnswers;

        //find AnswerCells belonging to Answer and delete both
        foreach($answers as $answer) {
            foreach($answer->getAnswerCells as $answerCell) {
                $answerCell->delete();
            }
            $answer->delete();
        }

        //find AnswerOptions belonging to Questions and delete both
        foreach($questions as $question) {
            foreach($question->getAnswerOptions as $answerOption) {
                $answerOption->delete();
            }
            $question->delete();
        }

        //finally delete survey
        $survey->delete();
        
        //Successmessage and redirect 
        Session::put('message', 'Umfrage gelöscht!' );
        Session::put('msgType', 'success');

        return Redirect::action('MonthController@currentMonth');
        //;
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        //find survey
        $survey = Survey::findOrFail($id);
        //find questions
        $questions = $survey->getQuestions;
        $questionCount = count($questions);
        //find answers
        $answers = $survey->getAnswers;
        //find all clubs
        $clubs = Club::all();

        $userId = Session::get('userId');
        $userGroup = Session::get('userGroup');

        //check if the role of the user allows edit/delete for all  answers
        $userGroup == 'admin' OR $userGroup == 'marketing' OR $userGroup == 'clubleitung' ? $userCanEditDueToRole = true : $userCanEditDueToRole = false;


        return view('surveyView', compact('survey', 'questions', 'questionCount', 'answers', 'clubs', 'userId', 'userGroup', 'userCanEditDueToRole'));
    }

    /**
     * @param  int $id
     * @return mixed
     */
    public function edit($id)
    {
        //find survey
        $survey = Survey::findOrFail($id);

        //find questions and answer options
        $questions = $survey->getQuestions;
        foreach($questions as $question)
            $answer_options = $question->getAnswerOptions;

        // prepare correct date and time format to be used in forms for deadline
        $time = strftime("%d-%m-%Y %H:%M:%S", strtotime($survey->deadline));
        
        return view('editSurveyView', compact('survey', 'questions', 'answer_options', 'time'));
    }

    /**
     * @param int $id
     * @param Request $input
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update($id, Request $input)
    {
        /*
        if ($input->password != $input->passwordDouble) {
            Session::put('message', Config::get('messages_de.password-mismatch') );
            Session::put('msgType', 'danger');
            return Redirect::back()->withInput();
        }
        */
        //find survey
        $survey = Survey::findOrFail($id);

        //edit existing survey
        $survey->title = $input->title;
        //if URL is in the description, convert it to clickable hyperlink
        $survey->description = $this->addLinks($input->description);

        //format deadline for database
        $survey->deadline = strftime("%Y-%m-%d %H:%M:%S", strtotime($input->deadline));
        $survey->is_anonymous = (bool) $input->is_anonymous;
        $survey->is_private = (bool) $input->is_private;
        $survey->show_results_after_voting = (bool) $input->show_results_after_voting;

        //delete password if user changes both to delete
        if ($input->password == "delete" AND $input->passwordDouble == "delete")
        {
            $survey->password = '';
        }
        //set new password
        elseif (!empty($input->password)
                AND !empty($input->passwordDouble)
                AND $input->password == $input->passwordDouble) {
            $survey->password = Hash::make($input->password);
        }

        $survey->save();

        //get questions and answer options as arrays from the input
        $questions_new = $input->questions;
        $answer_options_new = $input->answer_options;

        $required = $input->required;

        /* get question type as array
         * type = 0: no type given from user, delete question
         * 1: text field
         * 2: checkbox
         * 3: dropdown, has answer options!
         */
        $question_type = $input->type;

        //actual bug: answer options array from input is too long, must delete unnecessary elements
        for($i = count($answer_options_new)+1; $i >= count($questions_new); $i--) {
            unset($answer_options_new[$i]);
        }

        /*
         * array for the answer options doesn't have an entry for questions without answer options
         * better we fill missing keys, so we can iterate through it later
         * same problem with required array
         */
        for($i = 0; $i < count($questions_new); $i++) {
            if(array_key_exists($i, $answer_options_new) === False) {
                $answer_options_new[$i] = '';
            }
            if(empty($required) or array_key_exists($i, $required) === False) {
                $required[$i] = null;
            }
        }
        ksort($answer_options_new);
        ksort($required);

        //get questions and answer options from database
        $questions_db = $survey->getQuestions;
        $answer_options_db = [];
        foreach($questions_db as $question) {
            $answer_options_db[] = $question->getAnswerOptions;
        }

        //make old questions and answer options to arrays with objects as elements
        $questions_db = (array) $questions_db;
        $questions_db = array_shift($questions_db);
        for($i = 0; $i < count($answer_options_db); $i++) {
            $answer_options_db[$i] = (array) $answer_options_db[$i];
            $answer_options_db[$i] = array_shift($answer_options_db[$i]);
        }

        //if all questions are empty abort
        if(array_unique($questions_new) === array('')){
            Session::put('message', 'Es konnten keine Fragen geändert werden, 
                                     weil alle Fragen leer gelassen wurden!');
            Session::put('msgType', 'danger');
            return Redirect::back()->withInput();
        }

        //if no single field type is given abort
        if(empty($question_type) or array_unique($question_type) === array('0')){
            Session::put('message', 'Es wurden keine Fragen geändert, weil kein einziger Frage-Typ ausgewählt wurde!');
            Session::put('msgType', 'danger');
            return Redirect::back()->withInput();
        }

        //ignore empty questions
        for($i = 0; $i < count($questions_new); $i++) {

            //check if single empty questions exist or no question type is given
            if (empty($question) or $question_type[$i] == 0) {

                //ignore question
                unset($questions_new[$i]);
                unset($question_type[$i]);
                unset($answer_options_new[$i]);
                unset($required[$i]);

                //reindex arrays
                $questions_new = array_values($questions_new);
                $question_type = array_values($question_type);
                $answer_options_new = array_values($answer_options_new);
                $required = array_values($required);
            }
        }

        //ignore empty answer options
        for($i = 0; $i < count($answer_options_new); $i++) {

            //check if dropdown question and answer options exist
            if($question_type[$i] == 3 and is_array($answer_options_new[$i])) {
                //filter empty answer options and reindex
                $answer_options_new[$i] = array_values(array_filter($answer_options_new[$i]));

            }
        }

        //sort database question array by order
        usort($questions_db, array($this, "cmp"));

        //make question arrays have the same length
        if(count($questions_new) > count($questions_db)) {

            //more questions in input than in database
            //make new empty questions and push them to the database array
            for($i=count($questions_new); $i >= count($questions_db) ; $i--) {
                array_push($questions_db, new SurveyQuestion());

                //also push to the array for the database answer options
                //to make sure questions and answer option arrays have the same length
                array_push($answer_options_db, []);
            }
        }

        if(count($questions_db) > count($questions_new)) {

            //less questions in input than in database
            //delete unnecessary questions and answer options in database
            for($i=count($questions_db)-1; $i >= count($questions_new) ; $i--) {
                $question = SurveyQuestion::findOrFail($questions_db[$i]->id);

                foreach($question->getAnswerOptions as $answer_option) {
                    $answer_option->delete();
                }
                foreach($question->getAnswerCells as $answer_cell) {
                    $answer_cell->delete();
                }
                $question->delete();

                //delete questions in the arrays
                unset($questions_db[$i]);
                unset($answer_options_db[$i]);

                //reindexing
                $questions_db = array_values($questions_db);
                $answer_options_db = array_values($answer_options_db);

                //deleting the element in required and question type array is not necessary
            }
        }

        //question arrays should have the same length now so we can update questions
        for($i = 0; $i < count($questions_db); $i++) {

            //delete answer options if question type gets changed to something else than dropdown
            if($questions_db[$i]->field_type == 3 and $question_type[$i] != 3){
                $answer_options = $questions_db[$i]->getAnswerOptions;
                foreach($answer_options as $answer_option) {
                    $answer_option->delete();
                }
            }

            //delete answer cells if question type gets changed
            if($questions_db[$i]->field_type != $question_type[$i]){
                $answer_cells = $questions_db[$i]->getAnswerCells;
                foreach($answer_cells as $answer_cell) {
                    $answer_cell->delete();
                }
            }

            $questions_db[$i]->field_type = $question_type[$i];
            $questions_db[$i]->is_required = (bool) $required[$i];
            $questions_db[$i]->order = $i;
            $questions_db[$i]->question = $questions_new[$i];

            //survey_id has to be filled in case of new questions
            $questions_db[$i]->survey_id = $survey->id;

            $questions_db[$i]->save();
        }

        for($i = 0; $i < count($questions_db); $i++) {

            if ($questions_db[$i]->field_type == 3 and
             count($answer_options_new[$i]) > count($answer_options_db[$i])) {

                    //more answer options in input than in database
                    //make new empty answer options and push them to the database array
                    for($j = count($answer_options_new[$i]); $j >= count($answer_options_db[$i]); $j--) {
                        array_push($answer_options_db[$i], new SurveyAnswerOption());
                    }
             }

            if ($questions_db[$i]->field_type == 3 and
                count($answer_options_new[$i]) < count($answer_options_db[$i])) {

                //less answer options in input than in database
                //delete unnecessary answer options in database
                for($j = count($answer_options_db[$i])-1; $j >= count($answer_options_new[$i]); $j--) {
                    $answer_option = SurveyAnswerOption::findOrFail($answer_options_db[$i][$j]->id);
                    $answer_option->delete();

                    //also delete element in answer option array and reindex array keys
                    unset($answer_options_db[$i][$j]);
                    $answer_options_db[$i] = array_values(($answer_options_db[$i]));
                }
            }
        }

        //answer option arrays should have the same length now
        for($i = 0; $i < count($questions_db); $i++) {
            for($j = 0; $j < count($answer_options_db[$i]); $j++) {
                $answer_options_db[$i][$j]->survey_question_id = $questions_db[$i]->id;
                $answer_options_db[$i][$j]->answer_option = $answer_options_new[$i][$j];
                $answer_options_db[$i][$j]->save();
            }
        }

        return Redirect::action('SurveyController@show', array('id' => $survey->id));
    }


    /*
     *  -------------------- END OF REST-CONTROLLER --------------------
     */

    /**
     * @param $a
     * @param $b
     * @return int
     *
     * compares to elements which have an "order" attribute
     */
    private function cmp($a, $b)
    {
        if ($a->order == $b->order) {
            return 0;
        }
        return ($a->order < $b->order) ? -1 : 1;
    }

    /**
     * @param string $text
     * @return string
     */
    private function addLinks($text) {
        $text = preg_replace('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i',
            ' <a href="$1" target="_blank">$1</a> ',
            $text);
        $text = preg_replace('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i',
            '<a target="_blank" href="http://$1"  target="_blank">$1</a> ',
            $text);
        return $text;
    }

}
