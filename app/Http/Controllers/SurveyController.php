<?php

namespace Lara\Http\Controllers;

use Carbon\Carbon;
use Request;

use Lara\Http\Requests;
use Lara\Survey;
use DateTime;
use DateTimeZone;
use View;
use Input;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::all();
        
        return $surveys;
    }
    public function create($year = null, $month = null, $day = null)
    {

        //dont creates surveys, just return the createSurveyView with todays date
        // Filling missing date in case none is provided
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

    public function destroy()
    {
        //;
    }

    public function show($id)
    {
        $survey = Survey::findOrFail($id);

        //questions and answers need to be found and returned to View

        return view('surveyView', compact('survey'));
    }

    public function update()
    {
        //;
    }

    public function edit()
    {
        //;
    }
    public function store()
    {
        //stores default survey, needs user input in future
        $newSurvey = $this->editSurvey(null);

        Survey::create([$newSurvey]);
        return redirect('survey');
    }


    //private function, can only be called within the Controller
    private function editSurvey($id)
    {
        $survey = new Survey;

        //get existing survey by id
        if (!is_null($id))
        {
            $survey = Survey::findOrFail($id);
        }

        //get deadline from Form
        $survey->deadline = Input::get('deadline');

        //update deadline
        if (!empty(Input::get('deadline')))
        {
            $newDeadline = new DateTime(Input::get('deadline'), new DateTimeZone(Config::get('app.timezone')));
            $survey->deadline = $newDeadline->format('Y-m-d');
        }
        //if deadline is empty set new deadline, doesnt work at the moment
        else
        {
            $survey->deadline = date('Y-m-d', mktime(0, 0, 0, 12, 31, 2100));
        }
        return $survey;
    }
}
