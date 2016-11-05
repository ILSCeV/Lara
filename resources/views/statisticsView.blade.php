@extends('layouts.master')
@section('title')
    {{ trans('mainLang.statisticalEvaluation') }}
@stop

@section('content')
    <div class="row panel">
        <div class="col-md-4 col-sm-4 col-xs-4 no-padding h2">
            <a class="btn btn-default hidden-print pull-right"
               href="{{ Request::getBasePath() }}/statistics/{{ date("Y/m",
                                strtotime("previous month", mktime(0, 0, 0, $month, 1, $year))) }}">
                &lt;&lt;
            </a>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4 no-padding centered h2">
            {{ date('F Y', mktime(0, 0, 0, $month, 1, $year))}}
        </div>
        <div class="col-md-4 col-sm-4 col-xs-4 no-padding h2">
            <a class="btn btn-default hidden-print pull-left"
               href="{{ Request::getBasePath() }}/statistics/{{ date("Y/m", strtotime("next month", mktime(0, 0, 0, $month, 1, $year))) }}">
                &gt;&gt;
            </a>
        </div>
    </div>
    <div class="row">
        <div class="panel col-md-6 col-sm-12 col-xs-12 no-padding">
            <div>
                @include('partials.statisticsLeaderboards')
            </div>
        </div>

        <div class="panel col-xs-12 col-sm-12 col-md-6 no-padding-xs">
            <div>
                @include('partials.clubStatistics')
            </div>
        </div>
    </div>
@stop
