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
    <style>
        /*
    Label the data
    */
        <?php
        header("Content-Encoding: utf-8");
    ?>
        @media screen and (max-width: 978px) {
            #change-history td:nth-of-type(1):before {
                content: "Name";
                float: left;
            }

            #change-history td:nth-of-type(2):before {
                content: "Club";
                float: left;
            }

            <?php $count = 2; ?>
        @foreach($questions as $question)
        <?php $count += 1; ?>
        #change-history td:nth-of-type({{$count}}):before {
                content: "{{$question->question}}";
                float: left;
                display: inline-block;
                overflow: hidden;
            }

            @if($question->is_required == 1)
                                *

        @endif
        @endforeach


        }
    </style>
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

    {!! Form::open(['action' => ['SurveyAnswerController@store', $survey->id]]) !!}

    <div class="panel panel-warning">
        @if( $survey->password != '')
            <div class="hidden-print panel-heading">
                {!! Form::password('password', array('required',
                                                     'class'=>'col-md-4 col-xs-12 black-text',
                                                     'id'=>'password' . $survey->id,
                                                     'placeholder'=>'Passwort hier eingeben')) !!}
                <br>
            </div>
        @endif
    </div>

    <div class="panel" id="panelNoShadow">
        <div id="change-history" class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-responsive">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Club</th>
                    @foreach($questions as $question)
                        <th class="question">
                            {{$question->question}}
                            @if($question->is_required == 1)
                                *
                            @endif
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Name</td>
                    <td>Club</td>
                    @foreach($questions as $question)
                        <td class="question">
                            insert here
                        </td>
                    @endforeach
                </tr>
                @foreach($answers as $key => $answer)
                    <tr>
                        <td>{{$answer->name}}</td>
                        <td>
                            @if($club = $clubs->find($answer->club_id))
                                {{$club->clb_title}}
                            @else
                                kein Club
                            @endif
                        </td>
                        @foreach($answer->getAnswerCells as $cell)
                            <td class="singleAnswer">
                                {{$cell->answer}}
                            </td>
                            @endforeach
                            @if($userId == $answer->creator_id OR $userCanEditDueToRole)
                                    <!--Edid Delete Buttons-->
                            <td class="tdButtons panel" id="panelNoShadow">
                                <a href="#"
                                   class="editButton btn btn-primary "
                                   data-toggle="tooltip"
                                   data-placement="bottom">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a href="{{$survey->id}}/answer/{{$answer->id}}"
                                   class="btn btn-default "
                                   data-toggle="tooltip"
                                   data-placement="bottom"
                                   data-method="delete"
                                   data-token="{{csrf_token()}}"
                                   rel="nofollow"
                                   data-confirm="Möchtest Du diese Antwort wirklich löschen?">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                            @else
                                <td class="emptyNoButtons ">
                                </td>
                            @endif
                    </tr>
                @endforeach
                </tbody>
                <!--
                                    <tbody>
                                    <tr>
                                        <td>Jan</td>
                                        <td>C</td>
                                        <td>Antwort auf die Frage 1</td>
                                        <td>ja</td>
                                        <td>nein</td>
                                        <td>eine ziemlich lange Antwort mit viel bla bla und etc.</td>
                                        <td>A5</td>
                                        <td class="tdButtons">Buttons</td>
                                    </tr>
                                    <tr>
                                        <td>Lars</td>
                                        <td>Table cell</td>
                                        <td>Table cell</td>
                                        <td>Table cell</td>
                                        <td>Table cell</td>
                                        <td>Table cell</td>
                                        <td>Table cell</td>
                                        <td class="tdButtons">Buttons</td>
                                    </tr>
                                    <tr>
                                        <td>Fridolin</td>
                                        <td>Table cell</td>
                                        <td>Table cell</td>
                                        <td>Table cell</td>
                                        <td>Table cell</td>
                                        <td>Table cell</td>
                                        <td>Table cell</td>
                                        <td class="tdButtons">Buttons</td>
                                    </tr>
                                    </tbody>
                    -->
            </table>
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