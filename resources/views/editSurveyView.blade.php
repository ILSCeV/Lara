@extends('layouts.master')

@section('title')
    Umfrage editieren
@stop

@section('content')
    <h4>Umfrage editieren:</h4>

    {!! Form::model($survey, array('action' => ['SurveyController@update', $survey->id], 'method' => 'PATCH')) !!}
        @include('partials.surveyForm', ['submitButtonText' => 'Umfrage ändern'])
    {!! Form::close() !!}

@stop