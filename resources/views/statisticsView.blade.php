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
                   href="{{ Request::getBasePath() }}/statistics/{{ date("Y/m",
                                    strtotime("previous month", mktime(0, 0, 0, $month, 1, $year))) }}">
                    &lt;&lt;
                </a>

                <span class="btn btn-lg disabled mobile72Width" style="text-align: center !important;">
                    {{ date('F Y', mktime(0, 0, 0, $month, 1, $year))}}
                </span>

                <a class="btn btn-default hidden-print pull-left"
                   href="{{ Request::getBasePath() }}/statistics/{{ date("Y/m", strtotime("next month", mktime(0, 0, 0, $month, 1, $year))) }}">
                    &gt;&gt;
                </a>
           @else
                <a class="btn btn-default hidden-print"
                   href="{{ Request::getBasePath() }}/yearstatistics/{{ date("Y",
                                strtotime("previous year", mktime(0, 0, 0, $month, 1, $year))) }}">
                    &lt;&lt;
                </a>

                <span class="btn btn-lg disabled mobile72Width" style="text-align: center !important;">
                {{ date('Y', mktime(0, 0, 0, $month, 1, $year))}}
                </span>

                <a class="btn btn-default hidden-print pull-left"
                   href="{{ Request::getBasePath() }}/yearstatistics/{{ date("Y", strtotime("next year", mktime(0, 0, 0, $month, 1, $year))) }}">
                    &gt;&gt;
                </a>
           @endif
        </div>    

        {{-- placeholder for more buttons --}}
        <div class="col-xs-12 col-md-7 pull-right">
            <div class="form-inline">
                @if($isMonthStatistic)
                <div class="form-control">
                    <a class="btn btn-primary form-control" href="{{ action("StatisticsController@showYearStatistics") }}" >{{ trans("mainLang.yearStatistic") }}</a>
                </div>
                @else
                    <div class="form-control">
                        <a class="btn btn-primary form-control" href="{{ action("StatisticsController@showStatistics")  }}">{{ trans("mainLang.monthStatistic") }}</a>
                    </div>
                @endif
            </div>
        </div>

        <br/>
        <br class="hidden-xs">
        <br class="hidden-xs">
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
