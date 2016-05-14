@extends('layouts.master')

@section('title')
    Umfrage {{$survey->title}}
@stop

@section('content')

    <div class="btn-group col-md-6">
    <div class="row">
        <div class="panel no-padding">
            <h4 class="panel-title text-center">Umfrage {{ $survey->title }}</h4>
            <div class="panel-body">
                <h6>Beschreibung:</h6>
                {!! $survey->description!!}
                <div class="panel-footer">
                    Die Umfrage läuft noch bis: {{ strftime("%a, %d %b", strtotime($survey->deadline)) }} um
                    {{ date("H:i", strtotime($survey->deadline)) }}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel">
            <h5 class="panel-title text-center">Auswahlmöglichkeiten</h5>
            @foreach($questions as $question)
                <div class="row">
                    {{$question->content}}
                    @foreach($answers[$question->id] as $answer)
                        <div class="col-md-3">
                            {{ $answer->content }}
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

        <div class="row">
            <div class="panel padding-left-minimal">
            <h5 class="panel-title text-center">Antwort</h5>
                <form method="POST" action="/survey/{{ $survey->id }}/storeAnswer">

                    <div class="form-group">
                        <h6>Hier Antwort eingeben:</h6>
                        <textarea name="answer" class="form-control"></textarea>
                    </div>
                    <input name="survey_id" type="hidden" value="{{ $survey->id }}" />
                    <div class="form-group">
                    </div>
            </div>
                        <button type="submit" class="btn btn-primary">Abstimmen</button>
            <a href="javascript:history.back()" class="btn btn-default">Ohne Änderung zurück</a>
                    </div>
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                </form>
            </div>




<div class="btn-group col-md-6">
        <br class="visible-xs visible-sm">
        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">Bisherige Abstimmungen:</h4>
            </div>
            <div class="panel-body">
            <div class="form-group">
                    <i class="text-danger">Auswertung</i>
                    </div>
                </div>
            </div>
        </div>
@stop