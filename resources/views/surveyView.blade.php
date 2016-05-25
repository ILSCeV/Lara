@extends('layouts.master')
@section('title')
{{$survey->title}}
@stop
@section('content')
        <!--
        Title Box
        All relevant infos about survey are here!
    -->
<div class="panel no-padding">
    <div class="panel-body">
        <h4 class="panel-title">
            {{ $survey->title }}
        </h4>
        Beschreibung:
        @if($survey->description == null)
            keine Beschreibung vorhanden
        @else
            {{$survey->description }}
        @endif
        <br>
        {!! $survey->description!!}
        Die Umfrage läuft noch bis: {{ strftime("%a, %d %b", strtotime($survey->deadline)) }} um
        {{ date("H:i", strtotime($survey->deadline)) }}
    </div>
</div>
<!--
    Body Box
    Write Answers in here!

<div class="panel no-padding">
    <div class="panel-body">
        <h4 class="panel-title">
            Name, Verein, Frage 1, Frage 2
        </h4>
        hier kannst Du deine Sachen eintragen
        <br>
        Jan keiner Antwort1 A2
    </div>
</div>
-->

<!--
    Body Box
    Write Answers in here!
    Idea! How about each answer as a separate row?
-->
<div class="panel no-padding">
    <div class="panel-body">
        <!--Title of answer box-->
        <h4 class="panel-title">
            <div class="row">
                <div class="col-md-1">
                    Name
                </div>
                <div class="col-md-11">
                    Verein, Frage 1, Frage 2
                </div>
            </div>
        </h4>

        <!--new answer-->
        <div class="row">
            <div class="col-md-1">
                Textbox
            </div>
            <div class="col-md-11">
                dropdown, Textbox 1, Textbox 2
            </div>
        </div>
        
        <!--All answers until now-->
        <div class="row">
            <div class="col-md-1">
                Name
            </div>
            <div class="col-md-11">
                mein Verein, Antwort 1, Antwort 2
            </div>
        </div>
    </div>
</div>


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
    <div>
        @include('partials.surveyAnswer')
    </div>
    <div>
        @include('partials.surveyEvaluation')
    </div>
</div>
@stop