@if($clubEvent->evnt_date_start === date("Y-m-d", $weekDay->getTimestamp()))
    {{-- Workaround for older events withou evnt_show_to_club : use corresponding section, otherwise use all saved sections --}}
    <div class="word-break section-filter {{ empty($clubEvent->evnt_show_to_club) ? $clubEvent->section->title : join(" " ,json_decode($clubEvent->evnt_show_to_club)) }}">
        <div class="{{ Lara\EventStyler::classesForEvent($clubEvent) }}">
            @include("partials/month/clubEventInner")
        </div>
    </div>
@endif
