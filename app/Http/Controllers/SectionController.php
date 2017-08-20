<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Lara\Section;
use Lara\Utilities;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::orderBy('title', 'ASC')->paginate(25);

        return view('manageSections', ['sections' => $sections]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Utilities::requirePermission("Admin")) {
            // Return to the section management page
            Session::put('message', trans('mainLang.adminsOnly'));
            Session::put('msgType', 'danger');
        }

        $rules = array(
            'title' => 'required',
            'color' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        $title = Input::get("title");
        $id = Input::get("id");
        $color = Input::get("color");
        $isNew = isset($id);


        if ($validator->fails()) {
           return Redirect::back()->withErrors($validator);
        }


        if ($isNew) {
            $existingSection = Section::where('title', '=', $title)->first();
            if (!is_null($existingSection)) {
                // Return to the section management page
                Session::put('message', trans('mainLang.sectionExists'));
                Session::put('msgType', 'danger');
                return Redirect::back();
            }
            $section = new Section();
            $section->section_uid = hash("sha512", uniqid());
        } else {
            $section = Section::where('id', '=', $id)->first();
        }

        $section->title = $title;
        $section->color = $color;
        $section->save();

        // Return to the the section management page
        Session::put('message', trans('mainLang.changesSaved'));
        Session::put('msgType', 'success');
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get selected shiftTypes
        $current_section = Section::findOrFail($id);

        return View::make('sectionView', compact('current_section'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Lara\Section $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Lara\Section $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Section $section)
    {
        return self::store($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Lara\Section $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Section $section)
    {
        //
    }
}
