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
        <button type="submit" class="btn btn-primary">Löschen</button>
        <form action="{{ url('survey/'.$survey->id) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="submit" class="fa fa-btn fa-trash" id="archive"></button>
        </form>
        <!--TODO Kommentar: den Button an die richtige stelle bringen und richtigen Code geben -->
    </div>

    {!! Form::close() !!}

@stop