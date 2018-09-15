<?php

namespace Lara\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Lara\ClubEvent;
use Lara\Http\Middleware\ManagingUsersOnly;
use Lara\Logging;
use Lara\Role;
use Lara\Section;
use Lara\Shift;
use Lara\ShiftType;
use Lara\Template;
use Lara\Utilities;
use Lara\utilities\RoleUtility;
use Session;

class TemplateController extends Controller
{


    public function __construct()
    {
        $this->middleware(ManagingUsersOnly::class, ['except' => 'create']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Utilities::requirePermission(RoleUtility::PRIVILEGE_ADMINISTRATOR)) {
            $templatesQuery = Template::query();
        } else {
            $allowedSections = \Auth::user()->getRolesOfType(RoleUtility::PRIVILEGE_MARKETING)->map(function (Role $role) {
                return $role->section_id;
            })->toArray();
            $templatesQuery = Template::whereHas('section', function ($query) use ($allowedSections) {
                $query->whereIn('id', $allowedSections );
            });
        }
        $templates = $templatesQuery->orderBy('section_id')->orderBy('title')->get();
        return view('templateManagement', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->show(null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $templateId)
    {
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
        $timeStart = Input::get('beginTime');
        $timeEnd = Input::get('endTime');
        /** @var $template Template */
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
            'is_private' => $isPrivate,
            'section_id' => $section,
            'price_tickets_normal' => $priceTicketsNormal,
            'price_tickets_external' => $priceTicketsExternal,
            'price_normal' => $priceNormal,
            'price_external' => $priceExternal,
            'time_preparation_start' => $preparationTime,
            'public_info' => $publicInfo,
            'private_details' => $privateDetails,
            'facebook_needed' => $facebookNeeded,
            'time_start' => $timeStart,
            'time_stop' => $timeEnd
        ]);
        $template->save();

        $template->showToSection()->sync($filter);
        $shiftIds = $this->createShifts($inputShifts, $amount);
        $template->shifts()->sync($shiftIds);

        return redirect()->route('template.overview');
    }

    private function createShifts($inputShifts, $amount)
    {
        $results = [];
        for ($i = 0; $i < $amount; ++$i) {

            $title = $inputShifts["title"][$i];
            $id = $inputShifts["id"][$i];
            $type = $inputShifts["type"][$i];
            $start = $inputShifts["start"][$i];
            $end = $inputShifts["end"][$i];
            $weight = $inputShifts["weight"][$i];
            $optional = $inputShifts["optional"][$i] === "on"? 1 : 0;

            $position = $i;
            $shift = ShiftController::createShiftsFromEditSchedule($id, $title, $type, $start, $end, $weight, $position,null,$optional);
            if ($shift != null) {
                array_push($results, $shift->id);
            }
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
        /** @var Template $template */
        $template = Template::with('section')->firstOrNew(['id' => $templateId]);
        $sections = Section::all();
        if ($template->id == null) {
            /** @var Section $userSection */
            $userSection = \Auth::user()->section;

            $template->section_id = $userSection->id;
            $template->section = $userSection;
            $template->time_preparation_start = $userSection->preparationTime;
            $template->time_start = $userSection->startTime;
            $template->time_end = $userSection->endTime;
        }
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
    public function destroy($templateId)
    {
        $template = (new \Lara\Template)->where('id', $templateId)->with('shifts')->first();

        //
        $clubEvents = (new \Lara\ClubEvent)->where('template_id', '=', $template->id)->get();
        /** @var ClubEvent $clubEvent */
        foreach ($clubEvents as $clubEvent) {
            $clubEvent->template_id = null;
            $clubEvent->save();
        }

        $shifts = $template->shifts;
        foreach ($shifts as $shift) {
            $shift->delete();
        }
        $template->delete();

        Session::put('message', trans("mainLang.messageSuccessfulDeleted"));
        Session::put('msgType', 'success');

        return redirect()->route('template.overview');
    }
}
