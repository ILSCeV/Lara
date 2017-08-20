<?php

namespace Lara\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Lara\Section;

class SectionManagementController extends Controller
{
    public function store()
    {
        $rules = array(
            'title' => 'required',
            'color' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            //TODO: Error handling
        }
        $title = Input::get("title");
        $existingSection = Section::where('title', '=', $title)->first();
        if (!is_null($existingSection)) {
            //TODO: Error handling
        }

        if(is_null(Input::get("id"))){
            $section = new Section();
        } else {
            $section = Section::where('id','=',Input::get("id"))->first();
        }

        $section->title = $title;

    }

    public function update()
    {
    }

    public function delete()
    {
    }

    public function view()
    {
    }
}
