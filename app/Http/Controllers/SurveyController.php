<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;

use Carbon\Carbon;
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
        return View('createSurveyView', compact('date','time', 'questions'));
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

        //edit existing survey
        $survey->title = $input->title;
        $survey->description = $input->description;

        //if URL is in the description, convert it to clickable hyperlink
        $survey->description = preg_replace('$(https?://[a-z0-9_./?=&#-]+)(?![^<>]*>)$i',
            ' <a href="$1" target="_blank">$1</a> ',
            $survey->description);
        $survey->description = preg_replace('$(www\.[a-z0-9_./?=&#-]+)(?![^<>]*>)$i',
            '<a target="_blank" href="http://$1"  target="_blank">$1</a> ',
            $survey->description);

        //format deadline and in_calender for database
        $survey->deadline = strftime("%Y-%m-%d %H:%M:%S", strtotime($input->deadline));
        $survey->in_calendar = strftime("%Y-%m-%d", strtotime($input->in_calendar));
        //$survey->isAnonymous = &input->isAnonymous;
        //$survey->isSecretResult = §input->isSecretResult;

        //set up iteration variable for the order
        $iteration = 0;
        foreach ($input->questions as $question_id => $question) {
            //look up for the questions id in the database
            $question_db = SurveyQuestion::Find($question_id);
            if ($question_db === null) {
                //doesn't exist, make new model instance and fill it
                $question_db = new SurveyQuestion();
                $question_db->survey_id = $survey->id;
                $question_db->order = $iteration;
                $question_db->field_type = 1; //example
                $question_db->question = $question;
                $question_db->save();
            }
            else {
                //question exists in database

                //security risk: user manipulates source text and changes id manually
                //then hes able to edit every question in lara!
                $question_db->order = $iteration;
                $question_db->field_type = 1; //example
                $question_db->question = $question;
                $question_db->save();
            }
            //increment iteration so a new question will get an incremented order
            $iteration++;
        }
        
        $survey->save();

        //get updated questions for the view
        $questions = $survey->getQuestions;
        return view('surveyView', compact('survey','questions'));
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
