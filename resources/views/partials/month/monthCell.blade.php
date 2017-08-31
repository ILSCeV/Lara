{{-- Needs variables: $surveys, $events --}}

@foreach($surveys as $survey) {{-- going over all surveys see weekCellSurvey for a single survey--}}
    @include("partials/month/survey", compact("survey", "weekDay"))
@endforeach

@foreach($events as $clubEvent)
    @include("partials/month/clubEvent", compact("clubEvent", "weekDay"))
@endforeach





