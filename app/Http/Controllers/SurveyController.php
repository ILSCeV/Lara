<?php

namespace Lara\Http\Controllers;

use Config;
use Illuminate\Http\Request;
use Hash;
use Lara\Library\Revision;
use Lara\Person;
use Lara\Revision_SurveyAnswer;
use Lara\Revision_SurveyAnswerCell;
use Lara\RevisionEntry;
use Lara\SurveyAnswerCell;
use Session;
use Redirect;
use DateTime;

use Lara\Survey;
use Lara\SurveyQuestion;
use Lara\SurveyAnswer;
use Lara\SurveyAnswerOption;
use Lara\Club;
use Lara\Http\Requests\SurveyRequest;

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
        $time = $time->format('d-m-Y H:i:s');

        //placeholder because createSurveyView needs variable, can set defaults here
        $survey = new Survey();
        
        return view('createSurveyView', compact('survey','time'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SurveyRequest $request)
    {
        
        $survey = new Survey;
        $revision_survey = new Revision($survey);
        $survey->creator_id = Session::get('userId');
        $survey->title = $request->title;
        //if URL is in the description, convert it to clickable hyperlink
        $survey->description = $this->addLinks($request->description);
        $survey->deadline = strftime("%Y-%m-%d %H:%M:%S", strtotime($request->deadline));
        $survey->is_anonymous = isset($request->is_anonymous);
        $survey->is_private = isset($request->is_private);
        $survey->show_results_after_voting = isset($request->show_results_after_voting);
        
        if (!empty($request->password)
            AND !empty($request->password_confirmation)
            AND $request->password == $request->password_confirmation) {
            $survey->password = Hash::make($request->password);
        }

        $survey->save();
        $revision_survey->save($survey);

        $questions = $request->questions;
        $answer_options = $request->answer_options;

        /*
         * get question type as array
         * 1: text field
         * 2: checkbox
         * 3: dropdown, has answer options!
         */
        $questions_type = $request->type;
        $required = $request->required;

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
        
        //ignore empty answer options
        foreach($answer_options as $i => $answer_option) {

            //check if dropdown question and answer options exist
            if($questions_type[$i] == 3 and is_array($answer_options[$i])) {
                //filter empty answer options and reindex
                $answer_options[$i] = array_values(array_filter($answer_options[$i]));
            }
        }
        
        //make new question model instance, fill it and save it
        foreach($questions as $order => $question){
            $question_db = new SurveyQuestion();
            $revision_question = new Revision($question_db);
            $question_db->survey_id = $survey->id;
            $question_db->order = $order;
            $question_db->field_type = $questions_type[$order];
            $question_db->is_required = (bool) $required[$order];
            $question_db->question = $question;
            $question_db->save();
            $revision_question->save($question_db);

            //check if question is dropdown question
             if($questions_type[$order] == 3) {
                 foreach($answer_options[$order] as $answer_option) {
                     $answer_option_db = new SurveyAnswerOption();
                     $revision_option = new Revision($answer_option_db);
                     $answer_option_db->survey_question_id = $question_db->id;
                     $answer_option_db->answer_option = $answer_option;
                     $answer_option_db->save();
                     $revision_option->save($answer_option_db);
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
        $revision_survey = new Revision($survey);

        //find answers and questions that belong to SurveyID
        $questions = $survey->getQuestions;
        $answers = $survey->getAnswers;

        //find AnswerCells belonging to Answer and delete both
        foreach($answers as $answer) {
            foreach($answer->getAnswerCells as $answerCell) {
                $revision_cell = new Revision($answerCell);
                $answerCell->delete();
                $revision_cell->save($answerCell);
            }
            $revision_answer = new Revision($answer);
            $answer->delete();
            $revision_answer->save($answer);
        }

        //find AnswerOptions belonging to Questions and delete both
        foreach($questions as $question) {
            foreach($question->getAnswerOptions as $answerOption) {
                $revision_option = new Revision($answerOption);
                $answerOption->delete();
                $revision_option->save($answerOption);
            }
            $revision_question = new Revision($question);
            $question->delete();
            $revision_question->save($question);
        }

        //finally delete survey
        $survey->delete();
        $revision_survey->save($survey);
        
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


        $answers_with_trashed_ids = [];
        $answers_with_trashed = SurveyAnswer::withTrashed()->where('survey_id', $survey->id)->get();
        foreach($answers_with_trashed as $answer){
            $answers_with_trashed_ids[] = $answer->id;
        }

        $answer_cells_with_trashed_ids = [];
        $answer_cells_with_trashed = SurveyAnswerCell::withTrashed()->whereIn('survey_answer_id', $answers_with_trashed_ids)->get();
        foreach($answer_cells_with_trashed as $answer_cell) {
            $answer_cells_with_trashed_ids[] = $answer_cell->id;
        }

        $revisions_answer = \Lara\Revision::whereIn("object_id", $answers_with_trashed_ids)->where("object_name", "SurveyAnswer")->get();
        $revisions_cells = \Lara\Revision::whereIn("object_id", $answer_cells_with_trashed_ids)->where("object_name", "SurveyAnswerCell")->get();
        $revision_objects = $revisions_answer->merge($revisions_cells)->sortByDesc("created_at");
        $revisions[0]['entries'] = null;
        $i = 0;
        foreach($revision_objects as $revision) {
            $revisions[$i] = $revision->toArray();
            $revisions[$i]['entries'] = RevisionEntry::where("revision_id", $revision->id)->get()->toArray();
            $i++;
        }

//        ini_set("xdebug.var_display_max_depth", 10);
//        var_dump($revisions);



//        $revision_ids = [];
//        $answers_with_trashed = SurveyAnswer::withTrashed()->whereSurveyId($survey->id)->get();
//        foreach($answers_with_trashed as $answer) {
//            $revision_relations = Revision_SurveyAnswer::whereObjectId($answer->id)->get();
//            foreach($revision_relations as $revision_relation) {
//                array_push($revision_ids, $revision_relation->revision_id);
//            }
//
//            $answer_cells_with_trashed = SurveyAnswerCell::withTrashed()->whereSurveyAnswerId($answer->id)->get();
//            foreach($answer_cells_with_trashed as $answer_cell) {
//                $revision_relation_cells = Revision_SurveyAnswerCell::whereObjectId($answer_cell->id)->get();
//                foreach($revision_relation_cells as $revision_relation_cell) {
//                    array_push($revision_ids, $revision_relation_cell->revision_id);
//                }
//            }
//        }
//
//        $revision_objects = \Lara\Revision::whereIn("id", $revision_ids)->orderBy('created_at', 'desc')->get();
//        ini_set("xdebug.var_display_max_depth", 10);
//        $revisions = $revision_objects->toArray();
//        foreach($revision_objects as $key => $revision_object) {
//            var_dump($revisions[$key]['creator_id']);
////            not working because not every ldapid has an Person
////            (!empty($revisions[$key]['creator_id'])) ? $revisions[$key]['creator'] = Person::wherePrsnLdapId($revisions[$key]['creator_id'])->get(['prsn_name'])->first()->prsn_name : $revisions[$key]['creator'] = "Gast";
//            (!empty($revisions[$key]['creator_id'])) ? $revisions[$key]['creator'] = "ldap_id but not Person-entry" : $revisions[$key]['creator'] = "Gast";
//            $revisions[$key]['entries'] = $revision_object->getRevisionEntries->toArray();
//        }

        


        //check if the role of the user allows edit/delete for all  answers
        ($userGroup == 'admin' OR $userGroup == 'marketing' OR $userGroup == 'clubleitung') ? ($userCanEditDueToRole = true) : ($userCanEditDueToRole = false);


        //evaluation part

        //maybe sort questions by order here
        foreach($questions as $order => $question) {
            switch($question->field_type) {
                case 1: $evaluation[$order] = [];
                    break; //nothing to do here except pushing an element to the array that stands for the question
                case 2:
                    $evaluation[$order] = [
                        'Ja' => 0,
                        'Nein' => 0,
                        'keine Angabe' => 0
                    ];
                    foreach($question->getAnswerCells as $answerCell) {
                        switch($answerCell->answer) {
                            case 'Ja':
                                $evaluation[$order]['Ja'] += 1;
                                break;
                            case 'Nein':
                                $evaluation[$order]['Nein'] += 1;
                                break;
                            case 'keine Angabe':
                                $evaluation[$order]['keine Angabe'] += 1;
                                break;
                        }
                    }
                    break;
                case 3:
                    foreach ($question->getAnswerOptions as $answer_option) {
                        $evaluation[$order][$answer_option->answer_option] = 0;
                        foreach($question->getAnswerCells as $answerCell) {
                            if ($answer_option->answer_option === $answerCell->answer) {
                                $evaluation[$order][$answer_option->answer_option] += 1;
                            }
                        }
                    }
                    break;
            }
        }
        //todo: make $evaluation[$order][$answer_option->answer_option] a string (casting???)
        
        return view('surveyView', compact('survey', 'questions', 'questionCount', 'answers', 'clubs', 'userId',
            'userGroup', 'userCanEditDueToRole', 'evaluation', 'revisions'));
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
     * @param SurveyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update($id, SurveyRequest $request)
    {
        //find survey
        $survey = Survey::findOrFail($id);
        $revision_survey = new Revision($survey);

        //edit existing survey
        $survey->title = $request->title;
        //if URL is in the description, convert it to clickable hyperlink
        $survey->description = $this->addLinks($request->description);

        //format deadline for database
        $survey->deadline = strftime("%Y-%m-%d %H:%M:%S", strtotime($request->deadline));
        $survey->is_anonymous = (bool) $request->is_anonymous;
        $survey->is_private = (bool) $request->is_private;
        $survey->show_results_after_voting = (bool) $request->show_results_after_voting;

        //delete password if user changes both to delete
        if ($request->password == "delete" AND $request->password_confirmation == "delete")
        {
            $survey->password = '';
        }
        //set new password
        elseif (!empty($request->password)
                AND !empty($request->password_confirmation)
                AND $request->password == $request->password_confirmation) {
            $survey->password = Hash::make($request->password);
        }

        $survey->save();
        $revision_survey->save($survey);

        //get questions and answer options as arrays from the input
        $questions_new = $request->questions;
        $answer_options_new = $request->answer_options;

        $required = $request->required;

        /* get question type as array
         * 1: text field
         * 2: checkbox
         * 3: dropdown, has answer options!
         */
        $question_type = $request->type;

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

        //ignore empty answer options
        foreach($answer_options_new as $i => $answer_options_new) {

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
                    $revision_option = new Revision($answer_option);
                    $answer_option->delete();
                    $revision_option->save($answer_option);
                }
                foreach($question->getAnswerCells as $answer_cell) {
                    $revision_cell = new Revision($answer_cell);
                    $answer_cell->delete();
                    $revision_cell->save($answer_cell);
                }
                $revision_question = new Revision($question);
                $question->delete();
                $revision_question->save($question);

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
                    $revision_option = new Revision($answer_option);
                    $answer_option->delete();
                    $revision_option->save($answer_option);
                }
            }

            //delete answer cells if question type gets changed
            if($questions_db[$i]->field_type != $question_type[$i]){
                $answer_cells = $questions_db[$i]->getAnswerCells;
                foreach($answer_cells as $answer_cell) {
                    $revision_cell = new Revision($answer_cell);
                    $answer_cell->delete();
                    $revision_cell->save($answer_cell);
                }
            }
            
            $revision_question = new Revision($questions_db[$i]);
            $questions_db[$i]->field_type = $question_type[$i];
            $questions_db[$i]->is_required = (bool) $required[$i];
            $questions_db[$i]->order = $i;
            $questions_db[$i]->question = $questions_new[$i];

            //survey_id has to be filled in case of new questions
            $questions_db[$i]->survey_id = $survey->id;

            $questions_db[$i]->save();
            $revision_question->save($questions_db[$i]);
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
                $revision_option = new Revision($answer_options_db[$i][$j]);
                $answer_options_db[$i][$j]->survey_question_id = $questions_db[$i]->id;
                $answer_options_db[$i][$j]->answer_option = $answer_options_new[$i][$j];
                $answer_options_db[$i][$j]->save();
                $revision_option->save($answer_options_db[$i][$j]);
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
