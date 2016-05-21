<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;

use Lara\Http\Requests;

class SurveyAnswerController extends Controller
{
    /*
     * testing purposes only
     */
    public function show($surveyid, $id)
    {
        var_dump($surveyid, $id);
    }
    /**
     * @param Request $input
     */
    public function store(Request $input)
    {

    }

    public function update($id)
    {

    }

    public function destroy()
    {

    }
}
