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

        #dropdown_name {
            position: absolute;
            overflow: visible;
        }

        #dropdown-menu_name {
            position: absolute;
            top: 44px;
            left: 4%;
            right: 4%;
        }

        #dropdown_club {
            position: absolute;
            overflow: visible;
        }

        #dropdown-menu_club {
            position: absolute;
        }

        /*
    Label the data
    */
        <?php
        header("Content-Encoding: utf-8");
    ?>
        @media screen and (max-width: 978px) {
            #survey-answer td:nth-of-type(1):before {
                content: "Name";
                float: left;
            }

            #survey-answer td:nth-of-type(2):before {
                content: "Club";
                float: left;
            }

            #dropdown_name {
                position: absolute;
                overflow: visible;
            }

            #dropdown-menu_name {
                position: absolute;
                top: 44px;
                left: 50%;
                right: 50%;
            }

            #dropdown_club {
                position: relative;
                overflow: visible;
            }

            <?php $count = 2; ?>
        @foreach($questions as $question)
        <?php $count += 1; ?>
         @if($question->is_required == 1)
        #survey-answer td:nth-of-type({{$count}}):before {
                content: "{{$question->question}} *";
                float: left;
                display: inline-block;
                overflow: hidden;
            }

            @else
        #survey-answer td:nth-of-type({{$count}}):before {
                content: "{{$question->question}}";
                float: left;
                display: inline-block;
                overflow: hidden;
            }

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
            @if($survey->description != null)
                Beschreibung: {{$survey->description }}
            @endif
            <br>
            Die Umfrage läuft noch bis: {{ strftime("%a, %d %b", strtotime($survey->deadline)) }} um
            {{ date("H:i", strtotime($survey->deadline)) }}.
            <br>@if(count($answers) === 0) Es hat noch keine Person abgestimmt. @endif
                @if(count($answers) === 1) Es hat bereits eine Person abgestimmt. @endif
                @if(count($answers) > 1) Es haben bereits {{count($answers)}} Personen abgestimmt. @endif
        </div>
    </div>


    <br>
    <br>

    {!! Form::open(['action' => ['SurveyAnswerController@store', $survey->id]]) !!}

    <div class="panel panel-warning">
        @if( $survey->password != '')
            <div class="hidden-print panel-heading">
                {!! Form::password('password', array('class'=>'col-md-4 col-xs-12 black-text',
                                                     'id'=>'password' . $survey->id,
                                                     'placeholder'=>'Passwort hier eingeben')) !!}
                <br>
            </div>
        @endif
    </div>

    <div class="clubToQuestion">
    <div class="nameToQuestion">
    <div class="panel" id="panelNoShadow">
        <div id="survey-answer" class="table-responsive-custom">
            <table class="table table-striped table-bordered table-condensed table-responsive-custom">
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
                    <td id="dropdown_name" class="dropdown">
                        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'newName', 'placeholder' => 'mein Name', 'autocomplete' => 'off', 'required' => true, 'oninvalid' => 'setCustomValidity(\'Bitte gib deinen Namen ein\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
                        @if(!empty($userId))
                            <ul id="dropdown-menu_name" class="dropdown-menu dropdown-username">
                                <li id="yourself">
                                    <a href="javascript:void(0);"
                                       onClick="document.getElementById('newName').value='{{Session::get('userName')}}';
                                               document.getElementById('club').value='{{Session::get('userClub')}}';
                                               document.getElementById('ldapId').value='{{Session::get('userId')}}'">
                                        {{--document.getElementById('btn-submit-changes{{ ''. $testid }}').click();">--}}
                                        <b>Eigene Antwort</b>
                                    </a>
                                </li>
                            </ul>
                            {!! Form::hidden('ldapId', null, ['id' => 'ldapId']) !!}
                        @endif
                    </td>
                    <td>
                        {{--autocomplete for clubs is not working right now--}}
                        <div id="dropdown_club" class="dropdown">
                            <div class="btn-group no-padding">
                            {!! Form::text('club', null, ['class' => 'form-control', 'id' => 'club', 'placeholder' => 'mein Club', 'autocomplete' => 'off', 'required' => true, 'oninvalid' => 'setCustomValidity(\'Bist Du mitglied in einem Club?\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
                            </div>
                        <ul id="dropdown-menu_club" class="dropdown-menu dropdown-club"></ul>
                        </div>
                    </td>
                    @foreach($questions as $key => $question)
                        <td class="question">
                            @if($question->field_type == 1)
                                    <!-- Freitext -->
                            @if(!$question->is_required)
                                    <!--Answer not required-->
                            {!! Form::text('answers['.$key.']', null, ['rows' => 2, 'class' => 'form-control', 'placeholder' => 'Antwort hier hinzufügen']) !!}
                            @else
                                    <!--Answer* required-->
                            {!! Form::text('answers['.$key.']', null, ['required' => 'true', 'rows' => 2, 'class' => 'form-control', 'placeholder' => 'Antwort hier hinzufügen', 'oninvalid' => 'setCustomValidity(\'Bitte gib eine Antwort\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
                            @endif
                            @elseif($question->field_type == 2)
                                    <!-- Ja/Nein -->
                            {{ Form::radio('answers['.$key.']', 1) }} Ja
                            @if(!$question->is_required)
                                    <!--Answer not required-->
                            {{ Form::radio('answers['.$key.']', 0) }} Nein
                            {{ Form::radio('answers['.$key.']', -1, true)}} keine Angabe
                            @else
                                    <!--Answer* required-->
                            {{ Form::radio('answers['.$key.']', 0, true) }} Nein
                            @endif
                            @elseif($question->field_type == 3)
                                    <!-- Dropdown -->
                            <select class="form-control" name="answers[{{$key}}]">
                                @if(!$question->is_required)
                                    <option>keine Angabe</option>
                                @endif
                                @foreach($question->getAnswerOptions as $answerOption)
                                    <option>{{$answerOption->answer_option}}</option>
                                @endforeach
                            </select>
                            @endif
                        </td>
                    @endforeach
                    <td class="tdButtons " id="panelNoShadow">
                        {{--{!! Form::submit('<i class="fa fa-pencil"></i>', ['type' => 'submit', 'class' => 'btn btn-primary btn-margin', 'style' => 'display: inline-block;']) !!}
                        --}}
                        <button type="submit" class="btn btn-primary btn-margin" id="noMarginMobile"
                                style="display: inline-block;"><i class="fa fa-floppy-o"></i></button>
                        {!! Form::close() !!}
                    </td>
                </tr>
                @foreach($answers as $key => $answer)
                    <tr>
                        <td>
                            @include('partials.surveyAnswerStatus')
                            {{$answer->name}}
                        </td>
                        <td>
                            @if(!empty($answer->club))
                                {{$answer->club}}
                            @else
                                kein Club
                            @endif
                        </td>
                        @foreach($answer->getAnswerCells as $cell)
                            <td class="singleAnswer">
                                {{$cell->answer}}
                            </td>
                            @endforeach
                            @if($userId == $answer->creator_id OR $userCanEditDueToRole OR empty($answer->creator_id))
                                    <!--Edid Delete Buttons-->
                            <td class="tdButtons panel" id="panelNoShadow">
                                <a href="#"
                                   class="editButton btn btn-primary "
                                   data-toggle="tooltip"
                                   data-placement="bottom">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a href="{{$survey->id}}/answer/{{$answer->id}}"
                                   class="btn btn-default deleteRow"
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
                                <td class="tdButtons panel" id="panelNoShadow">
                                </td>
                            @endif
                    </tr>
                    @endforeach
                            <!-- Start of evaluation -->

                    <?php $i = 0; ?>
                    @foreach($answers as $key => $answer)
                        @if($i == 0)
                            <tr>
                                <td class="transparent background">bg</td>
                                <td class="transparent background">bg</td>
                                @foreach($answer->getAnswerCells as $cell)
                                    <td class="transparent background">bg</td>
                                @endforeach
                            </tr>
                        @endif
                        @if($i == 0)
                            <tr id="evaluation">
                                <td class="" id="whiteBackgroundTrasparent" ></td>
                                <td class="" id="whiteBackgroundTrasparent"></td>
                                @foreach($evaluation as $eva)
                                    <td class="mobileMarginTop" id="whiteBackground">
                                            <div>
                                                @if(empty($eva))
                                                    Freitext
                                                @endif
                                                @foreach($eva as $key => $evacount)
                                                    <div>{{$key}}: {{$evacount}} Personen</div>
                                                @endforeach
                                            </div>
                                    </td>
                                @endforeach
                            </tr>
                            @endif
                            <?php $i += 1; ?>
                            @endforeach
                                    <!-- End of evaluation -->
                </tbody>
            </table>
                    </div>
                </div>
            </div>
        </div>

{{---------------------------------------------change-history-----------------------------------------------------}}
        <br>
        <span class="hidden-xs">&nbsp;&nbsp;</span><span>&nbsp;&nbsp;</span>
        <a id="show-hide-history" class="text-muted hidden-print" href="#">
            Liste der Änderungen &nbsp;&nbsp;<i class="fa fa-caret-right" id="arrow-icon"></i>
        </a>

        <div class="panel hide" id="change-history">
            <div class=table-responsive>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Wer?</th>
                        <th>Zusammenfassung</th>
                        <th>Betroffene Spalte</th>
                        <th>Alter Wert</th>
                        <th>Neuer Wert</th>
                        <th>Wann?</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($revisions as $key => $revision)
                        @if($key > 0)
                            @if($revision['created_at'] != $revisions[$key-1]['created_at'])
                                <tr>
                                    <td>
                                        @if(empty($revision['creator_id']))
                                            Gast
                                        @else
                                            {{$revision['creator_id']}}
                                        @endif
                                    </td>
                                    <td>{{$revision['summary']}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{$revision['created_at']}}</td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td>
                                    @if(empty($revision['creator_id']))
                                        Gast
                                    @else
                                        {{$revision['creator_id']}}
                                    @endif
                                </td>
                                <td>{{$revision['summary']}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{$revision['created_at']}}</td>
                            </tr>
                        @endif
                            @foreach($revision['entries'] as $revision_entry)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>{{$revision_entry['changed_column_name']}}</td>
                                    <td>{{$revision_entry['old_value']}}</td>
                                    <td>{{$revision_entry['new_value']}}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                    @endforeach
                    </tbody>
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