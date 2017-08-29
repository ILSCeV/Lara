@extends('layouts.master')
@section('title')
    {{ trans('mainLang.statisticalEvaluation') }}
@stop

@section('content')

{{-- Restrict access to members only --}}
@if(Session::has('userId'))

    {{-- prev/next month --}}
    <div class="col-xs-12 col-md-12">
        <div class="col-xs-12 col-md-5 btn-group no-padding">
            @if($isMonthStatistic)
                <a class="btn btn-default hidden-print"
                   href="{{ action('StatisticsController@showStatistics') . date("/Y/m",
                                    strtotime("previous month", mktime(0, 0, 0, $month, 1, $year))) }}">
                    &lt;&lt;
                </a>

                <span class="btn btn-lg disabled mobile72Width" style="text-align: center !important;">
                    {{ date('F Y', mktime(0, 0, 0, $month, 1, $year))}}
                </span>

                <a class="btn btn-default hidden-print pull-left"
                   href="{{ action('StatisticsController@showStatistics') . date("/Y/m", strtotime("next month", mktime(0, 0, 0, $month, 1, $year))) }}">
                    &gt;&gt;
                </a>
           @else
                <a class="btn btn-default hidden-print"
                   href="{{ action('StatisticsController@showYearStatistics', date("Y", strtotime("previous year", mktime(0, 0, 0, $month, 1, $year))))  }}">
                    &lt;&lt;
                </a>

                <span class="btn btn-lg disabled mobile72Width" style="text-align: center !important;">
                {{ date('Y', mktime(0, 0, 0, $month, 1, $year))}}
                </span>

                <a class="btn btn-default hidden-print pull-left"
                   href="{{
                    action('StatisticsController@showYearStatistics', date("Y", strtotime("next year", mktime(0, 0, 0, $month, 1, $year)))) }}">
                    &gt;&gt;
                </a>
           @endif
        </div>    

        {{-- Month/year statstics selector --}}
        <div class="col-xs-12 col-md-7 btn-group pull-right">
            @if(\Lara\Utilities::requirePermission(array("admin","clubleitung")))
                    @if($isMonthStatistic)
                        <a class="btn btn-xs btn-primary pull-right"
                           type="button"
                           href="{{ action("StatisticsController@showYearStatistics") }}">
                            {{ trans("mainLang.yearStatistic") }}
                        </a>
                    @else
                        <a class="btn btn-xs btn-primary pull-right"
                           type="button"
                           href="{{ action("StatisticsController@showStatistics")  }}">
                            {{ trans("mainLang.monthStatistic") }}
                        </a>
                    @endif
            @endif
        </div>

        <br>
        <br>
        <br class="visible-xs">
    </div>


    <div class="row">

        {{-- Club member stats --}}
        <div class="col-md-7 col-sm-7 col-xs-12 no-padding">
            @include('partials.statisticsMembers')
        </div>

        {{-- Leaderboard --}}
        <div class="col-md-5 col-sm-5 col-xs-12 no-padding-xs">
            <br class="visible-xs">
            @include('partials.statisticsLeaderboards')
        </div>

    </div>

    {{-- JS helpers --}}
    <script>
        var chosenMonth = {{ $month }}; 
        var chosenYear = {{ $year }};
        var chosenPerson;
        var isMonthStatistic = {{ $isMonthStatistic }};
    </script>

@else
    @include('partials.accessDenied')
@endif

@stop
