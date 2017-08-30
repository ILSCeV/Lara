{{-- Needs variables: $surveys, $events --}}

@foreach($surveys as $survey) {{-- going over all surveys see weekCellSurvey for a single survey--}}

{{-- check if the survey is already over --}}
@if(strtotime($survey->deadline) < time())
    <?php $classString = "past-eventOrSurvey"; ?>
@else
    <?php $classString = ""; ?>
@endif

@if(date("Y-m-d", strtotime($survey->deadline)) === date("Y-m-d", $weekDay->getTimestamp()))
    @if(!Session::has('userId') && $survey->is_private)
        {{-- check current session for user role && and the survey for private status--}}
        {{-- no userId means this a guest account, so he gets blocked here--}}

        {{--if so show a grey placeholder for the guest--}}
        <div class="cal-event {{$classString}} dark-grey">
            <i class="fa fa-bar-chart-o white-text"></i>
            &nbsp;&nbsp;
            {{--and show him thats a private survey(=Interne Umfrage in german) only for users--}}
            <span class="white-text">
                    {{ trans('mainLang.internalSurvey') }}
                </span>
        </div>

    @else
        {{-- meaning Session::has'userId' OR !$survey->is_private == 0--}}
        {{-- so session has a valid user OR the guest can see this survey because it isn't private--}}
        <div class="cal-event {{$classString}} dark-grey calendar-internal-info">
            <i class="fa fa-bar-chart-o white-text"></i>
            &nbsp;&nbsp;
            {{-- provide a URL to the survey --}}
            <a href="{{ URL::route('survey.show', $survey->id) }}"
               data-toggle="tooltip" 
               data-placement="right"
               title="{{ trans('mainLang.showDetails')}}">
                {{-- instead of private survey show the actual title of the survey --}}
                <span class="white-text"> &nbsp;{{ $survey->title }} </span>
            </a>
        </div>
    @endif
@endif
@endforeach

@foreach($events as $clubEvent)
    @include("partials/month/clubEvent", compact("clubEvent", "weekDay"))
@endforeach





