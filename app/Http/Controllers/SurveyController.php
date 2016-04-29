<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;

use Lara\Http\Requests;

class SurveyController extends Controller
{
    public function index()
    {
        //;
    }
    public function create($year = null, $month = null, $day = null, $templateId = null)
    {
        // Filling missing date and template number in case none are provided
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
        return view('createSurveyView');
    }

    public function destroy()
    {
        //;
    }

    public function show()
    {
        //;
    }

    public function update()
    {
        //;
    }

    public function edit()
    {
        //;
    }

}
