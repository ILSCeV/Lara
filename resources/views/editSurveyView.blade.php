@extends('layouts.master')

@section('title')
    Umfrage editieren
@stop

@section('content')
    <div class="row">
        <div class="panel col-md-6 col-sm-12 col-xs-12">
    <h4>Umfrage editieren:</h4>

    {!! Form::model($survey, array('action' => ['SurveyController@update', $survey->id], 'method' => 'PATCH')) !!}
        @include('partials.surveyForm', ['submitButtonText' => 'Umfrage ändern'])

            </div>
        </div>

    <div class="form-group">
        {!! Form::submit("Umfrage ändern", ['class'=>'btn btn-primary']) !!}
        <br class="visible-xs">
        <a href="javascript:history.back()" class="btn btn-default">Ohne Änderung zurück</a>
    </div>

    {!! Form::close() !!}

@stop