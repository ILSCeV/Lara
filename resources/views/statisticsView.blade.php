@extends('layouts.master')
@section('title')
    {{ trans('mainLang.statisticalEvaluation') }}
@stop

@section('content')

{{-- Restrict access to members only --}}
@auth

    {{-- prev/next month --}}
    <div class="col-xs-12 col-md-12">
        <div class="col-xs-12 col-md-5 btn-group no-padding">
            @if($isMonthStatistic)
                <a class="btn btn-secondary hidden-print"
                   href="{{ action('StatisticsController@showStatistics') . date("/Y/m",
                                    strtotime("previous month", mktime(0, 0, 0, $month, 1, $year))) }}">
                    &lt;&lt;
                </a>

                <span class="btn btn-lg disabled mobile72Width" style="text-align: center !important;">
                    {{ date('F Y', mktime(0, 0, 0, $month, 1, $year))}}
                </span>

                <a class="btn btn-secondary hidden-print float-left"
                   href="{{ action('StatisticsController@showStatistics') . date("/Y/m", strtotime("next month", mktime(0, 0, 0, $month, 1, $year))) }}">
                    &gt;&gt;
                </a>
           @else
                <a class="btn btn-secondary hidden-print"
                   href="{{ action('StatisticsController@showYearStatistics', date("Y", strtotime("previous year", mktime(0, 0, 0, $month, 1, $year))))  }}">
                    &lt;&lt;
                </a>

                <span class="btn btn-lg disabled mobile72Width" style="text-align: center !important;">
                {{ date('Y', mktime(0, 0, 0, $month, 1, $year))}}
                </span>

                <a class="btn btn-secondary hidden-print float-left"
                   href="{{
                    action('StatisticsController@showYearStatistics', date("Y", strtotime("next year", mktime(0, 0, 0, $month, 1, $year)))) }}">
                    &gt;&gt;
                </a>
           @endif
        </div>    

        {{-- Month/year statstics selector --}}
        <div class="col-xs-12 col-md-7 btn-group float-right">
            @if($isMonthStatistic)
                <a class="btn btn-xs btn-primary float-right"
                   type="button"
                   href="{{ action("StatisticsController@showYearStatistics") }}">
                    {{ trans("mainLang.yearStatistic") }}
                </a>
            @else
                <a class="btn btn-xs btn-primary float-right"
                   type="button"
                   href="{{ action("StatisticsController@showStatistics")  }}">
                    {{ trans("mainLang.monthStatistic") }}
                </a>
            @endif
        </div>

        <br>
        <br>
        <br class="d-block d-sm-none">
    </div>


    <div class="row">

        {{-- Club member stats --}}
        <div class="col no-padding">
            @include('partials.statisticsMembers')
        </div>

        {{-- Leaderboard --}}
        <div class="col no-padding-xs">
            <br class="d-block.d-sm-none">
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
@endauth

@stop
