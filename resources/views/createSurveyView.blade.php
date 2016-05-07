@extends('layouts.master')

@section('title')
    Neue Umfrage erstellen
@stop

@section('content')
    <h4>Neue Umfrage erstellen:</h4>

    {!! Form::open(array('action' => 'SurveyController@store')) !!}
        @include('partials.surveyForm', ['submitButtonText' => 'Umfrage erstellen'])
    {!! Form::close() !!}

@stop