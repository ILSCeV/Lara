@extends('layouts.master')

@section('title')
    Neue Umfrage erstellen
@stop

@section('content')
    <div class="row">
        <div class="panel col-md-6 col-sm-12 col-xs-12">
    <h4>Neue Umfrage erstellen:</h4>

    {!! Form::open(array('action' => 'SurveyController@store')) !!}
        @include('partials.surveyForm', ['submitButtonText' => 'Umfrage erstellen'])
</div>
        </div>

            <div class="form-group">
                {!! Form::submit("Umfrage erstellen", ['class'=>'btn btn-primary']) !!}
                <br class="visible-xs">
                <a href="javascript:history.back()" class="btn btn-default">Ohne Änderung zurück</a>
            </div>
    {!! Form::close() !!}



@stop