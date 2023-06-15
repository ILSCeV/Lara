<?php

namespace Lara\Http\Controllers;


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
        $eventData->events = $events->toArray();
        return response()->json($eventData, 200);
    }
}
