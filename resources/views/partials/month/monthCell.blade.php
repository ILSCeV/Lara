{{-- Needs variables: $surveys, $events --}}

@foreach($surveys as $survey) {{-- going over all surveys see weekCellSurvey for a single survey--}}

    {{-- check if the survey is already over --}}
    @if(strtotime($survey->deadline) < time())
        <?php $classString = "past-eventOrSurvey"; ?>
    @else
        <?php $classString = ""; ?>
    @endif

    @if(date("Y-m-d", strtotime($survey->deadline)) === date("Y-m-d", $weekDay->getTimestamp()))
        @if(!Auth::user() && $survey->is_private)
            {{-- check current session for user role && and the survey for private status--}}
            {{-- no userId means this a guest account, so he gets blocked here--}}

            {{--if so show a grey placeholder for the guest--}}
            <div class="cal-event {{$classString}} palette-Grey-500 bg word-break section-filter survey">
                <i class="fa fa-bar-chart-o white-text"></i>
                &nbsp;&nbsp;
                {{--and show him thats a private survey(=Interne Umfrage in german) only for users--}}
                <span class="white-text event-name">
                        {{ trans('mainLang.internalSurvey') }}
                    </span>
            </div>

        @else
            {{-- meaning Session::has'userId' OR !$survey->is_private == 0--}}
            {{-- so session has a valid user OR the guest can see this survey because it isn't private--}}
            <div class="cal-event {{$classString}} palette-Purple-900 bg word-break section-filter survey">
                <i class="fa fa-bar-chart-o white-text"></i>
                &nbsp;&nbsp;<span class="event-time white-text">{{date ('H:i',strtotime($survey->deadline))}}</span>
                {{-- provide a URL to the survey --}}
                <a href="{{ URL::route('survey.show', $survey->id) }}"
                   class="event-name"
                   data-toggle="tooltip" 
                   data-placement="right"
                   title="{{ trans('mainLang.showDetails')}}">
                    {{-- instead of private survey show the actual title of the survey --}}
                    <span class="white-text"> {{ $survey->title }} </span>
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

        {{-- highlight with cal-month-my-event class if the signed in user has a shift in this event --}}
        @auth
            @if($clubEvent->hasShift(Auth::user()->person))
                <?php $classString .= " cal-month-my-event"; ?>
            @endif
        @endauth

        {{-- Filter --}}
        @if ( $clubEvent->showToSection->isEmpty() )
            {{-- Workaround for older events: if filter is empty - use event club data instead --}}
            <div class="{!! $clubEvent->section->title !!}">
        @else
            {{-- Normal scenario: add a css class according to filter data --}}
            <div class="section-filter @foreach($sections as $section) {!! in_array( $section->title, $clubEvent->showToSectionNames() ) ? $section->title : false !!} @endforeach">
        @endif


            {{-- guests see private events as placeholders only, so check if user is logged in --}}
            @guest
                {{-- show only a placeholder for private events --}}
                @if($clubEvent->evnt_is_private)
                    <div class="cal-event {{$classString}} palette-Grey-500 bg">
                        <i class="fa fa-eye-slash white-text"></i>
                        &nbsp;&nbsp;
                        <span class="white-text">{{ trans('mainLang.internEventP') }}</span>
                    </div>
                    {{-- show everything for public events --}}
                @else
                    @if     ($clubEvent->evnt_type == 0)
                        <div class="cal-event {{$classString}} palette-{!! $clubEvent->section->color !!}-700 bg">
                    @elseif ($clubEvent->evnt_type == 1)
                        <div class="cal-event {{$classString}} palette-Purple-500 bg">
                    @elseif ($clubEvent->evnt_type == 2 
                          || $clubEvent->evnt_type == 3)
                        <div class="cal-event {{$classString}} palette-{!! $clubEvent->section->color !!}-900 bg">
                    @elseif ($clubEvent->evnt_type == 4 
                          || $clubEvent->evnt_type == 5 
                          || $clubEvent->evnt_type == 6
                          || $clubEvent->evnt_type == 10
                          || $clubEvent->evnt_type == 11)
                        <div class="cal-event {{$classString}} palette-{!! $clubEvent->section->color !!}-500 bg">
                    @elseif ($clubEvent->evnt_type == 7 
                          || $clubEvent->evnt_type == 8)
                        <div class="cal-event {{$classString}} palette-{!! $clubEvent->section->color !!}-300 bg">
                    @elseif ($clubEvent->evnt_type == 9)
                    <div class="cal-event {{$classString}} palette-{!! $clubEvent->section->color !!}-500 bg">
                    @endif

                    @include("partials.event-marker", $clubEvent)
                        <span class="event-time white-text">&nbsp;{{  date ('H:i',strtotime($clubEvent->evnt_time_start))}}</span>
                    <a class="event-name" href="{{ URL::route('event.show', $clubEvent->id) }}"
                       data-toggle="tooltip" 
                       data-placement="right"
                       title="{{ trans('mainLang.showDetails')}}">
                        {{ $clubEvent->evnt_title }}
                    </a>
                </div>
                @endif

            
            {{-- show everything for members, but switch the color theme according to event type --}}
            @else       
                @if     ($clubEvent->evnt_type == 0)
                    <div class="cal-event {{$classString}} palette-{!! $clubEvent->section->color !!}-700 bg">
                @elseif ($clubEvent->evnt_type == 1)
                    <div class="cal-event {{$classString}} palette-Purple-500 bg">
                @elseif ($clubEvent->evnt_type == 2 
                      || $clubEvent->evnt_type == 3)
                    <div class="cal-event {{$classString}} palette-{!! $clubEvent->section->color !!}-900 bg">
                @elseif ($clubEvent->evnt_type == 4 
                      || $clubEvent->evnt_type == 5 
                      || $clubEvent->evnt_type == 6
                      || $clubEvent->evnt_type == 10
                      || $clubEvent->evnt_type == 11)
                    <div class="cal-event {{$classString}} palette-{!! $clubEvent->section->color !!}-500 bg">
                @elseif ($clubEvent->evnt_type == 7 
                      || $clubEvent->evnt_type == 8)
                    <div class="cal-event {{$classString}} palette-{!! $clubEvent->section->color !!}-300 bg">
                @elseif ($clubEvent->evnt_type == 9)
                    <div class="cal-event {{$classString}} palette-{!! $clubEvent->section->color !!}-500 bg">
                @endif

                    @include("partials.event-marker", $clubEvent)
                    {{-- Show starting time with Preparation time in () --}}
                    <span class="event-time white-text">
                        &nbsp;{{  date ('H:i',strtotime($clubEvent->evnt_time_start))}}{{$clubEvent->schedule->schdl_time_preparation_start <> $clubEvent->evnt_time_start?" (".date ('H:i',strtotime($clubEvent->schedule->schdl_time_preparation_start)).")":""}}
                    </span>
                {{--

                Disabling iCal until fully functional.

                @include("partials.publishStateIndicator")

                --}}

                    <a class="event-name" href="{{ URL::route('event.show', $clubEvent->id) }}"
                       data-toggle="tooltip" 
                       data-placement="right"
                       title="{{ trans('mainLang.showDetails')}}">
                        {{ $clubEvent->evnt_title }}
                    </a>

                </div>
            @endguest

        </div>

    @endif
@endforeach
