@extends('layouts.master')

@php
$queryParams='';
if($extraFilter!='')
{
    $queryParams='?filter='.$extraFilter;
}
@endphp

@section('title')
    {{ "KW" . $date['week'] . ": " . utf8_encode(strftime("%d. %b", strtotime($weekStart))) }} - {{ utf8_encode(strftime("%d. %b", strtotime($weekEnd))) }}
@stop

@section('moreScripts')
    <script src="{{asset(WebpackBuiltFiles::$jsFiles['autocomplete'])}}" ></script>
    <script>
        var extraFilter = '{{$extraFilter}}';
    </script>
@stop

@section('content')
    <div id="week-view-marker" class="container-fluid pb-3">
        <div class="row pb-3">
            {{-- Prev/next week selector --}}
            <div class="col-12 col-md-4 m-auto p-auto btn-group">
                <a class="btn hidden-print"
                   href="{{ Request::getBasePath() }}/calendar/{{$date['previousWeek']}}{{ $queryParams }}">
                    <i class="fas fa-chevron-left"></i>
                </a>

                <div class="row align-items-center mx-auto px-auto">
                    <h6 class="week-mo-so m-0 text-center">
                        {{ "KW" . $date['week']}}:
                        <br class="d-block d-sm-none">
                        {{ utf8_encode(strftime("%a %d. %B", strtotime($weekStart))) }} -
                        <br class="d-block d-sm-none">
                        {{ utf8_encode(strftime("%a %d. %B", strtotime($weekEnd . '- 2 days'))) }}
                    </h6>

                    <h6 class="week-mi-di m-0 text-center hide">
                        {{ "KW" . $date['week']}}:
                        <br class="d-block d-sm-none">
                        {{ utf8_encode(strftime("%a %d. %B", strtotime($weekStart . '+  2 days'))) }} -
                        <br class="d-block d-sm-none">
                        {{ utf8_encode(strftime("%a %d. %B", strtotime($weekEnd))) }}
                    </h6>
                </div>

                <a class="btn hidden-print"
                   href="{{ Request::getBasePath() }}/calendar/{{$date['nextWeek']}}{{ $queryParams }}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>

            {{-- Section filter --}}
            <div class="col-12 col-md-8 p-0 m-0 d-print-none" id="section-filter">
                @include('partials.filter')

                {{-- Week filters --}}
                <div class="h-25"></div>
                <div class="row float-right">
                    <div class="btn-toolbar pt-2" role="toolbar">
                        {{-- show time button Ger.: Zeiten einblenden --}}
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm hidden-print" type="button" id="toggle-shift-time">
                                {{ trans('mainLang.shiftTime') }}
                            </button>

                            {{-- hide taken shifts button Ger.: Vergebenen Diensten ausblenden --}}
                            <button class="btn btn-sm hidden-print" type="button" id="toggle-taken-shifts">
                                {{ trans('mainLang.hideTakenShifts') }}
                            </button>
                        </div>

                        {{-- show/hide all comment fields --}}
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm hidden-print" type="button" id="toggle-all-comments">
                                {{ trans('mainLang.comments') }}
                            </button>
                        </div>

                        {{-- week: Monday - Sunday button Ger.: Woche: Montag - Sonntag --}}
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-primary hidden-print" type="button" id="toggle-week-start">
                                {{ trans('mainLang.weekStart') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-columns">
            {{-- weekdays --}}
            @if (!$events->isEmpty())
                    @foreach($events as $clubEvent)
                        {{-- Filter: we add a css class later below if a club is mentioned in filter data --}}
                        {{-- we compare the current week number with the week the event happens in
                                         to catch and hide any events on mondays and tuesdays (day < 3) next week
                                         in Mo-So or alternatively mondays/tuesdays this week in Mi-Di view. --}}
                        @php
                            $elementClass = 'element-item private section-filter ';
                            foreach($sections as $section){
                                if(in_array( $section->id, $clubEvent->showToSectionIds() )){
                                $elementClass.=" section-" . $section->id;
                                }
                            }
                            if ( date('W', strtotime($clubEvent->evnt_date_start)) === $date['week']
                                      && date('N', strtotime($clubEvent->evnt_date_start)) < 3 ) {
                                      $elementClass.=' week-mo-so';
                            } elseif (date("W", strtotime($clubEvent->evnt_date_start) )
                                          === date("W", strtotime("next Week".$weekStart))
                                          && date('N', strtotime($clubEvent->evnt_date_start)) < 3) {
                                          $elementClass.=' week-mi-di hide';
                            }
                            if($clubEvent->evnt_is_private){
                               $elementClass.=' private';
                            }

                        @endphp
                        <div class="p-2 mb-3 {{$elementClass}}">
                            {{-- guests see private events as placeholders only, so check if user is logged in --}}
                            @guest
                                @if($clubEvent->evnt_is_private)
                                    @include('partials.weekCellHidden')
                                    {{-- show public events, but protect members' entries from being changed by guests --}}
                                @else
                                    @include('partials.weekCellProtected')
                                @endif
                                {{-- show everything for members --}}
                            @else
                                {{-- members see both private and public events, but still need to manage color scheme --}}
                                @include('partials.weekCellFull')
                            @endguest
                        </div>
                    @endforeach
                    @include('partials.weekView.survey')
    </div>
    @else
        <br>
        <div class="card rounded" style="margin: 16px;">
            <div class="card-header rounded-top">
                <h5>{{ trans('mainLang.noEventsThisWeek') }}</h5>
            </div>
        </div>
        <div class="d-flex flex-wrap">
            @include('partials.weekView.survey')
        </div>
    @endif
    <div class="col-md-12 col-12">
        {{-- Legend --}}
        @include("partials.legend")

        {{-- filter hack --}}
        <span id="week-view-marker" hidden>&nbsp;</span>
    </div>
@stop
