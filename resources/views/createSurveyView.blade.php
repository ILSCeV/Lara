@extends('layouts.master')

@section('title')
    {{ trans('mainLang.createNewSurvey') }}
@stop
@section('moreScripts')
    <script src="{{asset(WebpackBuiltFiles::$jsFiles['survey'])}}"></script>
@endsection

@section('moreStylesheets')
    <style>
        .dropdown-toggle {
            text-transform: capitalize;
        }
    </style>
@stop

@section('content')
    <div class="row">
        {!! Form::open(array('action' => 'SurveyController@store')) !!}
            @include('partials.surveyForm')
        {!! Form::close() !!}
    </div>
@stop
ph
