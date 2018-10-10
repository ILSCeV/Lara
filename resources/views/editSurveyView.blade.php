@extends('layouts.master')

@section('title')
    {{ trans('mainLang.editSurvey') }}
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
        {!! Form::model($survey, array('action' => ['SurveyController@update', $survey->id], 'method' => 'PATCH')) !!}
            @include('partials.surveyForm', ['submitButtonText' => 'Umfrage ändern'])
        {!! Form::close() !!}
    </div>
@stop
