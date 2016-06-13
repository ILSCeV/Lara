<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;

use Lara\Survey;
use Lara\SurveyQuestion;
use View;
use Input;

class SurveyController extends Controller
{
    public function index()
    {

    }
    public function create($year = null, $month = null, $day = null)
    {
        //dont creates surveys, just returns the createSurveyView with todays date
        if ( is_null($year) ) {
            $year = date("Y");
        }

        if ( is_null($month) ) {
            $month = date("m");
        }

        if ( is_null($day) ) {
            $day = date("d");
        }

        // prepare correct date and time format to be used in forms for deadline/in_calendar
        $date = strftime("%d-%m-%Y", strtotime($year.$month.$day));
        $time = strftime("%d-%m-%Y %H:%M:%S", strtotime($year.$month.$day));

        //surveyForm uses $questions for editing purposes
        //has here to be filled with standard questions like this
        /*$questions = [array("id" => 20,
                            "survey_id" => 22,
                            "order" => 0,
                            "field_type" => 1,
                            "question" => "Kommst du?",
                            "createdAt" => Carbon::now(),
                            "updatedAt" => Carbon::now()
                        ),
                        array("id" => 21,
                            "survey_id" => 22,
                            "order" => 1,
                            "field_type" => 1,
                            "question" => "Bringst du jemanden mit?",
                            "createdAt" => Carbon::now(),
                            "updatedAt" => Carbon::now()
                        )
                    ];
         */
        return View('createSurveyView', compact('date','time','questions'));
    }

    /**
     * @param Request $input
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $input)
    {
        $survey = new Survey;
        $survey->creator_id = Session::get('userId');
        $survey->title = $input->title;
        //if URL is in the description, convert it to clickable hyperlink
        $survey->description = $this->addLinks($input->description);
        $survey->is_anonymous = isset($input->is_anonymous);
        $survey->is_private = isset($input->is_private);
        $survey->show_results_after_voting = isset($input->show_results_after_voting);

        $survey->deadline = strftime("%Y-%m-%d %H:%M:%S", strtotime($input->deadline));
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

            return Redirect::action('SurveyController@create', array('id' => $survey->id));
        }

        //abort if no single field type is given
        if(empty($questions_type) and array_unique($questions_type) === array('0')){
            Session::put('message', 'Es konnten keine Fragen gespeichert werden, 
                                     weil kein einziger Frage-Typ angegeben wurde!');
            Session::put('msgType', 'danger');

            return Redirect::action('SurveyController@create', array('id' => $survey->id));
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
            $question_db->required = (bool) $required[$order];
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
     * "getAnswers" does not work as expected, right now use this:
     * SurveyAnswer::whereSurveyQuestionId($question->id)->get()
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        //find survey
        $survey = Survey::findOrFail($id);

        //find questions
        $questions = $survey->getQuestions;

        //find answers(as collection object) to an array with the questionid as index
        foreach($questions as $question) {
            $answers[$question->id] = $question->getAnswers;
        }
        return view('surveyView', compact('survey', 'questions', 'answers'));
    }

    public function edit($id)
    {
        //find survey
        $survey = Survey::findOrFail($id);

        //find questions and answer options
        $questions = $survey->getQuestions;
        foreach($questions as $question)
            $answer_options = $question->getAnswerOptions;

        // prepare correct date and time format to be used in forms for deadline/in_calendar
        $date = strftime("%d-%m-%Y", strtotime($survey->in_calendar));
        $time = strftime("%d-%m-%Y %H:%M:%S", strtotime($survey->deadline));
        return view('editSurveyView', compact('survey', 'questions', 'answer_options', 'date', 'time'));
    }
    
    public function update($id, Request $input)
    {
        //find survey
        $survey = Survey::findOrFail($id);

        //edit existing survey
        $survey->title = $input->title;
        //if URL is in the description, convert it to clickable hyperlink
        $survey->description = $this->addLinks($input->description);


        //format deadline and in_calender for database
        $survey->deadline = strftime("%Y-%m-%d %H:%M:%S", strtotime($input->deadline));
        $survey->in_calendar = strftime("%Y-%m-%d", strtotime($input->in_calendar));
        //$survey->isAnonymous = &input->isAnonymous;
        //$survey->isSecretResult = §input->isSecretResult;

        //get questions and answer options from database
        $questions_db = $survey->getQuestions;
        foreach($questions_db as $question) {
            $answer_options_db[] = $question->getAnswerOptions;
        }

        //old questions and answer options to arrays with objects as elements
        $questions_db = (array) $questions_db;
        $questions_db = array_shift($questions_db);
        for($i = 0; $i < count($answer_options_db); $i++) {
            $answer_options_db[$i] = (array) $answer_options_db[$i];
            $answer_options_db[$i] = array_shift($answer_options_db[$i]);
        }

        //get questions and answer options from the input
        $questions_new = $input->questions;
        $answer_options_new = $input->answer_options;

        //ignore empty questions and answer options
        foreach($questions_new as $i => $question) {
            if (empty($question)) {

                //ignore answer options from empty question
                foreach ($answer_options_new[$i] as $j => $answer_option) {
                    unset($answer_options_new[$i][$j]);
                    $answer_options_new[$i] = array_values(array_filter($answer_options_new[$i]));
                }

                //ignore question itself
                unset($questions_new[$i]);
                $questions_new = array_values(array_filter($questions_new));
            }

            //check if empty answer options exist
            foreach($answer_options_new[$i] as $k => $answer_option) {
                if (empty($answer_option)) {
                    unset($answer_options_new[$i][$k]);
                    $answer_options_new[$i] = array_values(array_filter($answer_options_new[$i]));
                }
            }
        }

        //sort database array by order
        usort($questions_db, array($this, "cmp"));

        //make question arrays have the same length
        if(count($questions_new) > count($questions_db)) {

            //more questions in input
            //make new empty questions and push them to the database array
            for($i=count($questions_new); $i >= count($questions_db) ; $i--) {
                array_push($questions_db, new SurveyQuestion());

                //also push to the array for the database answer options
                //to make sure questions and answer option arrays have the same length
                array_push($answer_options_db, []);
            }
        }
        //return var_dump($answer_options_db);
        if(count($questions_db) > count($questions_new)) {

            //less questions in input
            //delete unnecessary questions and answer options in database
            for($i=count($questions_db); $i > count($questions_new) ; $i--) {
                $question = SurveyQuestion::FindOrFail($questions_db[$i]->id);
                foreach($question->getAnswerOptions() as $key => $answer_option) {
                    $answer_option->delete();
                }
                $question->delete();

                //delete questions in the arrays
                unset($questions_db[$i]);
                unset($answer_options_db[$i]);

                //reindexing
                $questions_db = array_values(array_filter($questions_db));
                $answer_options_db = array_values(array_filter($answer_options_db));
            }
        }

        //all arrays should have the same length now
        for($i = 0; $i < count($questions_db); $i++) {

            //check if question text or field type was updated
            if (strcmp($questions_db[$i]->question, $questions_new[$i]) !== 0 or
                $questions_db[$i]->field_type !== 1) {

                //is updated question, change it
                $questions_db[$i]->order = $i;
                $questions_db[$i]->question = $questions_new[$i];

                //survey_id has to be filled in case of new questions
                $questions_db[$i]->survey_id = $survey->id;
                $questions_db[$i]->field_type = 1; //example

                //todo: make answer options array from database have the answer options in input

                //check if field_type is dropdown now
                //question has answer options
                /*
                for($j = 0; $j < count($answer_options_db[$i]); $j++) {
                    $answer_options_db[$i][$j] = $answer_options_new[$i][$j];
                }
                */
                $questions_db[$i]->save();
            }
        }

        $survey->save();

        //get updated questions for the view
        $questions = $survey->getQuestions;
        foreach($questions as $question) {
            $answer_options = $question->getAnswerOptions;
        }
        return view('surveyView', compact('survey','questions', 'answer_options'));
    }


    public function destroy()
    {
        //;
    }

    /*
     *  -------------------- END OF REST-CONTROLLER --------------------
     */

    private function cmp($a, $b)
    {
        if ($a->order == $b->order) {
            return 0;
        }
        return ($a->order < $b->order) ? -1 : 1;
    }

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
