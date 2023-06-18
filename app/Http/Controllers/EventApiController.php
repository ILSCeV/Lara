<?php

namespace Lara\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Lara\EventApi\EventData;
use Lara\EventView;
use Lara\Section;

class EventApiController extends Controller
{
    public function getEventsFor($sectionName)
    {
        $section = Section::where('title', '=', $sectionName)->first();
        if (!$section) {
            return Response::make("not found", 404);
        }

        $events = EventView::where('section_id', '=', $section->id)->get()->map(function (EventView $data) {
            return $data->toEvent();
        });

        $eventData = new EventData();
        $eventData->generated_on = Carbon::now()->toIso8601ZuluString();
        $eventData->events = $events->toArray();
        $json = preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', json_encode($eventData));
        return response($json)->header('Content-Type', 'application/json');
    }
}
