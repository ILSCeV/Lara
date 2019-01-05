@extends('layouts.master')

@section('title')
    {{ $date['monthName'] . " " . $date['year'] }}
@stop

@section('moreScripts')
    <script src="{{asset(WebpackBuiltFiles::$jsFiles['monthview'])}}" ></script>
@stop

@section('content')

{{-- Prev/next month selector --}}
    <div class="row pb-3">
        <div class="col-xs-12 col-md-3 m-auto p-auto btn-group">
            <a class="btn hidden-print"
               href="{{ Request::getBasePath() }}/calendar/{{ date("Y/m",
                                strtotime("previous month", $date['startStamp'])) }}">
                <i class="fas fa-chevron-left"></i>
            </a>

            <span class="row align-items-center mx-auto px-auto">
                <strong>{{ $date['monthName'] . " " . $date['year'] }}</strong>
            </span>

            <a class="btn hidden-print"
               href="{{ Request::getBasePath() }}/calendar/{{ date("Y/m", strtotime("next month", $date['startStamp'])) }}">
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- Section filter -->
        <div class="col-xs-12 col-md-9 p-0 m-0 d-print-none" id="section-filter">
            @include('partials.filter')
        </div>
    </div>

    <div id="monthTableContainer" class="container-fluid">
        <div class="progress col-xs-12 col-md-5">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        </div>
    </div>

    <div class="col-12">
        {{-- Legend --}}
        @include("partials.legend")

        {{-- filter hack --}}
        <span id="month-view-marker" hidden>&nbsp;</span>
    </div>
    <script>
       var year = {{ $date['year'] }};
       var month = {{ $date['month'] }};
       var isAuthenticated = {{ Auth::hasUser() }};
    </script>
@stop
