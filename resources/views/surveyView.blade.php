<!-- useable variables:
$survey
$questions
$questionCount
$answers
$clubs
$userId
$userGroup
$userCanEditDueToRole
-->
@extends('layouts.master')
@section('title')
    {{$survey->title}}
@stop
@section('moreStylesheets')
    <link rel="stylesheet" media="all" type="text/css" href="{{ asset('/css/surveyViewStyles.css') }}"/>
    <script src="js/surveyView-scripts.js"></script>
@stop
@section('moreScripts')
    <script src="{{ asset('js/surveyView-scripts.js') }}"></script>
@stop
@section('content')


    <div class="panel no-padding">
        <div class="panel-title-box">
            <h4 class="panel-title">
                {{ $survey->title }}
            </h4>
        </div>
        <div class="panel-body">
            Beschreibung:
            @if($survey->description == null)
                keine Beschreibung vorhanden
            @else
                {{$survey->description }}
            @endif
            <br>
            Die Umfrage läuft noch bis: {{ strftime("%a, %d %b", strtotime($survey->deadline)) }} um
            {{ date("H:i", strtotime($survey->deadline)) }}. Es haben bereits {{count($answers)}} Personen abgestimmt.
        </div>
    </div>


    <br>
    <br>


    <div class="panel">
        <div id="change-history">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed table-responsive">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Club</th>
                        <th>Frage 1</th>
                        <th>Frage 2</th>
                        <th>Frage 3</th>
                        <th>Frage 4</th>
                        <th>Frage 5</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Jan</td>
                        <td>C</td>
                        <td>Antwort auf die Frage 1</td>
                        <td>ja</td>
                        <td>nein</td>
                        <td>eine ziemlich lange Antwort mit viel bla bla und etc.</td>
                        <td>A5</td>
                    </tr>
                    <tr>
                        <td>Lars</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                    </tr>
                    <tr>
                        <td>Fridolin</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                        <td>Table cell</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>





    <script>
        $(document).ready(function () {
            $('#surveyAnswerForm').formValidation();
        });
        $(document).ready(function () {
            $('#surveyAnswerFormMobile').formValidation();
        });
    </script>

@stop