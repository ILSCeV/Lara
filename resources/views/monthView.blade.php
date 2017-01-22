@extends('layouts.master')

@section('title')
    {{ $date['monthName'] . " " . $date['year'] }}
@stop

@section('moreStylesheets')
    <link rel="stylesheet" media="all" type="text/css" href="{{ asset('/css/monthViewResponsive.css') }}"/>
@stop

@section('content')
    <!-- prev/next month -->
    <div class="col-xs-12 col-md-12">
        <div class="col-xs-12 col-md-5 btn-group no-padding">
            <a class="btn btn-default hidden-print"
               href="{{ Request::getBasePath() }}/calendar/{{ date("Y/m",
                                strtotime("previous month", $date['startStamp'])) }}">
                &lt;&lt;
            </a>

            <span class="btn btn-lg disabled mobile72Width" style="text-align: center !important;">
                {{ $date['monthName'] . " " . $date['year'] }}
            </span>
            <a class="btn btn-default hidden-print"
               href="{{ Request::getBasePath() }}/calendar/{{ date("Y/m", strtotime("next month", $date['startStamp'])) }}">
                &gt;&gt;
            </a>
        </div>

        <!-- Section filter -->
        <div class="col-xs-12 col-md-7 no-padding">
            <div class="pull-right">
                @include('partials.filter')
            </div>
        </div>

        <br class="hidden-xs">
        <br class="hidden-xs">
    </div>

    {{-- Month Table --}}
    <div class="col-xs-12 bgWhite col-md-12 calendarWrapper">
        <div class=" hidden-xs" id="ContentRow">
            <div class="calendarWeek noBorderTop" style="border-top: 0px">
                {{ trans('mainLang.Cw') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.Mo') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.Tu') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.We') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.Th') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.Fr') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.Sa') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.Su') }}
            </div>
        </div>

        <div class="calendarRow clearfix group noHeightMobile hidden-xs">
            <!--This is an empty row-->
            <!--Without this row, the first real calendar row would disapear-->
        </div>
        <!--Print Weeks on left side-->
        @foreach($mondays as $weekStart)
            {{-- Add one week to the week start to get the next monday --}}
            <?php $weekEnd = new DateTime($weekStart->format('Y-m-d')); $weekEnd->modify('+1 week') ?>
            @if ($weekStart->format('W') === date('W'))
                {{-- Current week --}}
                <div class="calendarRow clearfix group WeekMarkerRow" >
                    <div class="calendarWeek WeekMarker">
                        <a href="{!! Request::getBasePath() !!}/calendar/{{$weekStart->format('Y\/\K\WW')}}"
                           data-toggle="tooltip" 
                           data-placement="top"
                           title="{{ trans('mainLang.showWeek')}}">
                            <span class="onlyOnMobile">{{ trans('mainLang.Cw') }}</span> {{$weekStart->format('W')}}.
                        </a>
                    </div>
                    {{-- Foreach on DatePeriod excludes the last day, so we iterate over Monday to Sunday--}}
                    @foreach(new DatePeriod($weekStart, new DateInterval('P1D'), $weekEnd) as $weekDay)
                        @include('partials.month.day', ['month' => $date['month']])
                    @endforeach
                </div>
            @else
                {{-- Not current week --}}
                <div class="calendarRow clearfix group">
                    <div class="calendarWeek ">
                        <a href="{!! Request::getBasePath() !!}/calendar/{{$weekStart->format('Y\/\K\WW')}}"
                           data-toggle="tooltip" 
                           data-placement="top"
                           title="{{ trans('mainLang.showWeek')}}">
                            <span class="onlyOnMobile">{{ trans('mainLang.Cw') }}</span> {{$weekStart->format('W')}}.
                        </a>
                    </div>
                    @foreach(new DatePeriod($weekStart, new DateInterval('P1D'), $weekEnd) as $weekDay)
                        @include('partials.month.day', ['month' => $date['month']])
                    @endforeach
                </div>
            @endif
        @endforeach

    </div>

    <div class="col-md-12 col-xs-12">
        {{-- Legend --}}
        @include("partials.legend")

        {{-- filter hack --}}
        <span id="month-view-marker" hidden>&nbsp;</span>
    </div>

@stop