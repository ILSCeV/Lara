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
        return View('createSurveyView', compact('date','time'));
    }

    /**
     * @param Request $input
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $input)
    {
        $survey = new Survey;
        $survey->prsn_id = Session::get('userId');
        $survey->title = $input->title;
        $survey->description = $input->description;

        //if URL is in the description, convert it to clickable hyperlink
        $survey->description = preg_replace('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i',
            ' <a href="$1" target="_blank">$1</a> ',
            $survey->description);
        $survey->description = preg_replace('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i',
            '<a target="_blank" href="http://$1"  target="_blank">$1</a> ',
            $survey->description);

        $survey->deadline = strftime("%Y-%m-%d %H:%M:%S", strtotime($input->deadline));
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

    public function update($id, Request $input)
    {

        //find survey
        $survey = Survey::findOrFail($id);

        $questions = $survey->getQuestions;
        foreach($questions as $question) {
            $answers[$question->id] = $question->getAnswers;
        }

        //edit existing survey
        $survey->prsn_id = Session::get('userId');
        $survey->title = $input->title;
        $survey->description = $input->description;

        //if URL is in the description, convert it to clickable hyperlink
        $survey->description = preg_replace('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i',
            ' <a href="$1" target="_blank">$1</a> ',
            $survey->description);
        $survey->description = preg_replace('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i',
            '<a target="_blank" href="http://$1"  target="_blank">$1</a> ',
            $survey->description);

        $survey->deadline = strftime("%Y-%m-%d %H:%M:%S", strtotime($input->deadline));
        $survey->in_calendar = strftime("%Y-%m-%d", strtotime($input->in_calendar));
        //$survey->isAnonymous = &input->isAnonymous;
        //$survey->isSecretResult = §input->isSecret Result;
        $survey->save();

        return view('surveyView', compact('survey','questions','answers'));
    }

    public function edit($id)
    {
        //find survey
        $survey = Survey::findOrFail($id);

        //find questions
        $questions = $survey->getQuestions;

        // prepare correct date and time format to be used in forms for deadline/in_calendar
        $date = strftime("%d-%m-%Y", strtotime($survey->in_calendar));
        $time = strftime("%d-%m-%Y %H:%M:%S", strtotime($survey->deadline));
        return view('editSurveyView', compact('survey', 'questions', 'date', 'time'));
    }
}
