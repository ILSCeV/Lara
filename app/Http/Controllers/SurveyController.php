<?php

namespace Lara\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use Redirect;

//use Lara\Http\Requests;
use Lara\Survey;
use Lara\SurveyQuestion;
use Lara\SurveyAnswer;
use DateTime;
use DateTimeZone;
use View;
use Input;

class SurveyController extends Controller
{
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

        // prepare correct date format to be used in the forms
        $date = strftime("%d-%m-%Y", strtotime($year.$month.$day));
        return View('createSurveyView', compact('date'));
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
        $survey->description = $input->description;

        //if URL is in the description, convert it to clickable hyperlink
        $survey->description = preg_replace('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i',
            ' <a href="$1" target="_blank">$1</a> ',
            $survey->description);
        $survey->description = preg_replace('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i',
            '<a target="_blank" href="http://$1"  target="_blank">$1</a> ',
            $survey->description);

        $survey->deadline = strftime("%Y-%m-%d %H-%M-%S", strtotime($input->deadline));
        $survey->in_calendar = strftime("%Y-%m-%d", strtotime($input->in_calendar));
        $survey->save();

        foreach($input->questions as $order => $question){
            $question_db = new SurveyQuestion();
            $question_db->survey_id = $survey->id;
            $question_db->order = $order;
            $question_db->field_type = 1; //example
            $question_db->question = $question;
            $question_db->save();
        }

        return Redirect::action('SurveyController@show', array('id' => $survey->id));
    }


    public function destroy()
    {
        //;
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

    public function update($id)
    {
        //find survey
        $survey = Survey::findOrFail($id);

        //find questions and answers
        $questions = Survey::findOrFail($survey->getQuestions->id);
        $answers = SurveyQuestion::findOrFail($questions->getAnswers->survey_question_number);

        //edit survey
        $survey = $this->editSurvey($id);

        //save everything
        $survey->save();

        return view('surveyView', compact('survey','questions','answers'));
    }

    public function edit($id)
    {
        //find survey
        $survey = Survey::findOrFail($id);

        //find questions and answers
        $questions = $survey->getQuestions;

        $date= $survey->deadline;

        return view('editSurveyView', compact('survey', 'questions', 'date'));
    }

    //private function, can only be called within the Controller
    private function editSurvey($id)
    {
        $survey = new Survey;

        //get existing survey by id if provided
        if (!is_null($id))
        {
            $survey = Survey::findOrFail($id);
        }

        //get deadline from Form
        $survey->deadline = Input::get('deadline');

        //update deadline
        if (!empty(Input::get('deadline')))
        {
            $newDeadline = new DateTime(Input::get('deadline'), mktime(0, 0, 0, 12, 31, 2100));
            $survey->deadline = $newDeadline->format('Y-m-d');
        }

        //if deadline is empty set new deadline, doesnt work at the moment
        else
        {
            $survey->deadline = date('Y-m-d', mktime(0, 0, 0, 12, 31, 2100));
        }
        return view('surveyView', compact($survey->id));
    }

}
