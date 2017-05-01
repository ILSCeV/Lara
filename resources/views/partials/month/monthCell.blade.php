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
    @if($clubEvent->evnt_date_start === date("Y-m-d", $weekDay->getTimestamp()))
        {{--Check if the event is still going on--}}
        @if(strtotime($clubEvent->evnt_date_end.' '.$clubEvent->evnt_time_end) < time())
            {{-- The event is already over --}}
            <?php $classString = "past-eventOrSurvey"; ?>
        @else
            <?php $classString = ""; ?>
        @endif

        {{-- highlight with cal-month-my-event class if the signed in user has an entry in this event --}}
        @if((Session::has('userId') && $clubEvent->hasShift($clubEvent->getSchedule->id, Session::get('userId'))))
            <?php $classString .= " cal-month-my-event"; ?>
        @endif

        {{-- Filter --}}
        @if ( empty($clubEvent->evnt_show_to_club) )
            {{-- Workaround for older events: if filter is empty - use event club data instead --}}
            <div class="{!! $clubEvent->getPlace->plc_title !!}  word-break">
        @else
            {{-- Normal scenario: add a css class according to filter data --}}
            <div class="word-break section-filter @foreach($sections as $section) {!! in_array( $section->plc_title, json_decode($clubEvent->evnt_show_to_club) ) ? $section->plc_title : false !!} @endforeach">
        @endif

            {{-- guests see private events as placeholders only, so check if user is logged in --}}
            @if(!Session::has('userId'))
                {{-- show only a placeholder for private events --}}
                @if($clubEvent->evnt_is_private)
                    <div class="cal-event {{$classString}} dark-grey">
                        <i class="fa fa-eye-slash white-text"></i>
                        &nbsp;&nbsp;
                        <span class="white-text">{{ trans('mainLang.internEventP') }}</span>
                    </div>
                    {{-- show everything for public events --}}
                @else
                    @if ($clubEvent->evnt_type == 1)
                        <div class="cal-event {{$classString}} calendar-public-info">
                    @elseif ($clubEvent->evnt_type == 6 OR $clubEvent->evnt_type == 9)
                        <div class="cal-event {{$classString}} calendar-public-task">
                    @elseif ($clubEvent->evnt_type == 7 OR $clubEvent->evnt_type == 8)
                        <div class="cal-event {{$classString}} calendar-public-marketing">
                    @elseif ($clubEvent->getPlace->id == 1)
                        <div class="cal-event {{$classString}} calendar-public-event-bc-club">
                    @elseif ($clubEvent->getPlace->id == 2)
                        <div class="cal-event {{$classString}} calendar-public-event-bc-cafe">
                    @endif
                    @include("partials.event-marker", $clubEvent)
                    &nbsp;&nbsp;
                    <a href="{{ URL::route('event.show', $clubEvent->id) }}"
                       data-toggle="tooltip" 
                       data-placement="right"
                       title="{{ trans('mainLang.showDetails')}}">
                        {{ $clubEvent->evnt_title }}
                    </a>
                </div>
                @endif

            {{-- show everything for members, but switch the color theme according to event type --}}
            @else
                @if($clubEvent->evnt_is_private)
                    @if ($clubEvent->evnt_type == 1)
                        <div class="cal-event {{$classString}} calendar-internal-info">
                    @elseif ($clubEvent->evnt_type == 6 OR $clubEvent->evnt_type == 9)
                        <div class="cal-event {{$classString}} calendar-internal-task">
                    @elseif ($clubEvent->evnt_type == 7 OR $clubEvent->evnt_type == 8)
                        <div class="cal-event {{$classString}} calendar-internal-marketing">
                    @elseif ($clubEvent->getPlace->id == 1)
                        <div class="cal-event {{$classString}} calendar-internal-event-bc-club">
                    @elseif ($clubEvent->getPlace->id == 2)
                        <div class="cal-event {{$classString}} calendar-internal-event-bc-cafe">
                    @else
                        {{-- DEFAULT --}}
                        <div class="cal-event {{$classString}} dark-grey">
                    @endif
                @else
                    @if     ($clubEvent->evnt_type == 1)
                        <div class="cal-event {{$classString}} calendar-public-info">
                    @elseif ($clubEvent->evnt_type == 6 OR $clubEvent->evnt_type == 9)
                        <div class="cal-event {{$classString}} calendar-public-task">
                    @elseif ($clubEvent->evnt_type == 7 OR $clubEvent->evnt_type == 8)
                        <div class="cal-event {{$classString}} calendar-public-marketing">
                    @elseif ($clubEvent->getPlace->id == 1)
                        <div class="cal-event {{$classString}} calendar-public-event-bc-club">
                    @elseif ($clubEvent->getPlace->id == 2)
                        <div class="cal-event {{$classString}} calendar-public-event-bc-cafe">
                    @else
                        {{-- DEFAULT --}}
                        <div class="cal-event {{$classString}} dark-grey">
                    @endif
            @endif
            @include("partials.event-marker", $clubEvent)
            @include("partials.publishStateIndicator")
            &nbsp;&nbsp;
            <a href="{{ URL::route('event.show', $clubEvent->id) }}"
               data-toggle="tooltip" 
               data-placement="right"
               title="{{ trans('mainLang.showDetails')}}">
                {{ $clubEvent->evnt_title }}
            </a>

        </div>

    @endif
</div>
@endif
@endforeach





