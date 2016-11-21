@extends('layouts.master')
@section('title')
    {{ trans('mainLang.personalStatistics') }}
@stop

@section('content')

    <div class="row">
        {{-- Most done shift--}}
        <div class="col-md-7 col-sm-7 col-xs-12 no-padding">
            <div>Your most done shift is</div>
        </div>

        {{-- Least done shift--}}
        <div class="col-md-5 col-sm-5 col-xs-12 no-padding-xs">
            <br class="visible-xs">
            <div> Your least done shift is</div>

        </div>
    </div>
    <div class="row">
        <div id="activityGraph">

        </div>
    </div>
@stop

@section('moreScripts')
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>
    <script src="{{ asset('js/personalStatistics.js') }}"></script>
@stop
