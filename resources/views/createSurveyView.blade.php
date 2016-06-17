@extends('layouts.master')

@section('title')
    Neue Umfrage erstellen
@stop

@section('content')
    <div class="row">


    {!! Form::open(array('action' => 'SurveyController@store')) !!}
        @include('partials.surveyForm', ['submitButtonText' => 'Umfrage erstellen'])

        </div>
    &nbsp;
            <div class="form-group">
                {!! Form::submit("Umfrage erstellen", ['class'=>'btn btn-primary']) !!}
                &nbsp;&nbsp;&nbsp;&nbsp;
                <br class="visible-xs">
                <a href="javascript:history.back()" class="btn btn-default">Ohne Änderung zurück</a>
            </div>
    {!! Form::close() !!}



@stop