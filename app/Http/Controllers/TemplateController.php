<?php

namespace Lara\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Lara\ClubEvent;
use Lara\Logging;
use Lara\Section;
use Lara\Shift;
use Lara\ShiftType;
use Lara\Template;
use Lara\Utilities;
use Session;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!(Session::has('userId')
            && (Session::get('userGroup') == 'marketing'
                || Session::get('userGroup') == 'clubleitung'
                || Session::get('userGroup') == 'admin'))) {
            Session::put('message', trans("messages.notAllowed"));
            Session::put('msgType', 'danger');
            return redirect('/');
        }

        //
        if (Utilities::requirePermission("admin")) {
            $templates = Template::all();
        } else {
            $templates = Template::whereHas('section', function ($query) {
                $query->where('title', '=', Session::get('userClub'));
            })->get();
        }
        $templates = $templates->sortBy('title');
        return view('templateManagement', compact('templates'));
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
    public function store(Request $request, $templateId)
    {
        //
        if (!(Session::has('userId')
            && (Session::get('userGroup') == 'marketing'
                || Session::get('userGroup') == 'clubleitung'
                || Session::get('userGroup') == 'admin'))) {
            Session::put('message', trans("messages.notAllowed"));
            Session::put('msgType', 'danger');
            return redirect('/');
        }

        $title = Input::get('title');
        $subtitle = Input::get('subtitle');
        $type = Input::get('type');
        $isPrivate = (Input::get('isPrivate') == '1') ? 0 : 1;
        $section = Input::get('section');
        $filter = collect(Input::get("filter"))->values()->toArray();
        $priceTicketsNormal = Input::get('priceTicketsNormal');
        $priceTicketsExternal = Input::get('priceTicketsExternal');
        $priceNormal = Input::get('priceNormal');
        $priceExternal = Input::get('priceExternal');
        $preparationTime = Input::get('preparationTime');
        $publicInfo = Input::get('publicInfo');
        $privateDetails = Input::get('privateDetails');
        $facebookNeeded = Input::get('facebookNeeded');
        /** @var $template Template*/
        $template = Template::firstOrNew(["id" => $templateId]);
        $inputShifts = Input::get("shifts");
        $amount = count($inputShifts["title"]);

        $currentShiftIds = $inputShifts["id"] ? $inputShifts["id"] : [];
        $template->shifts()
            ->whereNotIn('id', $currentShiftIds)
            ->get()
            ->each(function (Shift $shift) {
                Logging::shiftDeleted($shift);
                $shift->delete();
            });

        $template->fill([
            'title' => $title,
            'subtitle' => $subtitle,
            'type' => $type,
            'isPrivate' => $isPrivate,
            'section_id' => $section,
            'priceTicketsNormal' => $priceTicketsNormal,
            'priceTicketsExternal' => $priceTicketsExternal,
            'priceNormal' => $priceNormal,
            'priceExternal' => $priceExternal,
            'preparationTime' => $preparationTime,
            'publicInfo' => $publicInfo,
            'privateDetails' => $privateDetails,
            'facebook_needed' => $facebookNeeded]);
        $template->save();

        $template->showToSection()->sync($filter);
        $shiftIds = $this->createShifts($inputShifts,$amount);
        $template->shifts()->sync($shiftIds);

        return redirect()->route('template.overview');
    }

    private function createShifts($inputShifts,$amount){
        $results = [];
        for ($i = 0; $i < $amount; ++$i) {

            $title = $inputShifts["title"][$i];
            $id = $inputShifts["id"][$i];
            $type = $inputShifts["type"][$i];
            $start = $inputShifts["start"][$i];
            $end = $inputShifts["end"][$i];
            $weight = $inputShifts["weight"][$i];

            $position = $i;

            $shift = Shift::firstOrNew(["id" => $id]);

            // If there was a shifttype passed and one with the correct title exists, use this one
            // Otherwise create a new model
            $oldShiftType = $shift->type;

            // we need a raw statement for case sensitivity
            $shiftType = ShiftType::whereRaw("BINARY `title`= ?", $title)
                ->where(function($query) use($type, $start, $end){
                    $query->where('id', $type);
                    $query->orWhere('start', $start);
                    $query->where('end', $end);
                })
                ->first();
            if (is_null($shiftType)) {
                $shiftType = new ShiftType([
                    "id" => $type,
                    "title" => $title,
                    'start' => $start,
                    'end' => $end,
                    'statistical_weight' => $weight
                ]);
                $shiftType->save();
            }

            // if the model was newly created, save the new shiftType
            $shift->fill([
                "start" => $start,
                "end" => $end,
                "statistical_weight" => $weight,
                "shifttype_id" => $shiftType->id,
                "position" => $position
            ]);

            if ($shift->exists) {
                if ($shift->isDirty('shifttype_id')) {
                    Logging::shiftTypeChanged($shift, $oldShiftType, $shiftType);
                }

                if ($shift->isDirty('statistical_weight')) {
                    Logging::shiftStatisticalWeightChanged($shift);
                }

                if ($shift->isDirty('start')) {
                    Logging::shiftStartChanged($shift);
                }

                if ($shift->isDirty('end')) {
                    Logging::shiftEndChanged($shift);
                }
            }
            $shift->save();
            array_push($results, $shift->id);
        }
        return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param  \Lara\Template $template
     * @return \Illuminate\Http\Response
     */
    public function show($templateId)
    {
        //
        if (!(Session::has('userId')
            && (Session::get('userGroup') == 'marketing'
                || Session::get('userGroup') == 'clubleitung'
                || Session::get('userGroup') == 'admin'))) {
            Session::put('message', trans("messages.notAllowed"));
            Session::put('msgType', 'danger');
            return redirect('/');
        }

        $template = Template::where('id', $templateId)->with('section')->first();
        $sections = Section::all();
        $shifts = $template->shifts()->get();
        // get a list of available job types
        $shiftTypes = ShiftType::where('is_archived', '=', '0')
            ->orderBy('title', 'ASC')
            ->get();

        return view('editTemplateView', compact('template', 'sections', 'shifts', 'shiftTypes'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Lara\Template $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Lara\Template $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Lara\Template $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        if (!(Session::has('userId')
            && (Session::get('userGroup') == 'marketing'
                || Session::get('userGroup') == 'clubleitung'
                || Session::get('userGroup') == 'admin'))) {
            Session::put('message', trans("messages.notAllowed"));
            Session::put('msgType', 'danger');
            return redirect('/');
        }

        //
        $clubEvents = ClubEvent::where('template_id','=',$template->id)->get();
        /** @var ClubEvent $clubEvent */
        foreach ($clubEvents as $clubEvent){
            $clubEvent->template_id = null;
            $clubEvent->save();
        }

        $shifts = $template->shifts()->get();
        foreach ($shifts as $shift){
            $shift->delete();
        }
        Template::destroy($template->id);

        return redirect()->route('template.overview');
    }
}
