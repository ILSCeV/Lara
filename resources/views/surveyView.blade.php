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

        #question_row {
            height: 50px
        }

        <?php header("Content-Encoding: utf-8"); ?>
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

            #question_row {
                height: auto
            }

        <?php $count = 2; ?>
        @foreach($questions as $question)
            <?php $count += 1; ?>
             @if($question->is_required == 1)
              {{--if a question is set to required show a * if the user didn't fill it in--}}
                #survey-answer td:nth-of-type({{$count}}):before {
                        content: "{{$question->question}} *";
                        float: left;
                        display: inline-block;
                        overflow: hidden;
                        white-space: normal;
                        height: 100%;
                    }

                    @else
                #survey-answer td:nth-of-type({{$count}}):before {
                        content: "{{$question->question}}";
                        float: left;
                        display: inline-block;
                        overflow: hidden;
                        white-space: normal;
                        height: 100%;
                    }
            @endif
        @endforeach
        }
    </style>
@stop
@section('moreScripts'){{--collection of used java script functions to clean up the code--}}
    <script src="{{ asset('js/surveyView-scripts.js') }}"></script>
@stop
@section('content')
    
    <div class="panel no-padding">
        <div class="panel-title-box">
            <h4 class="panel-title">
                {{ $survey->title }}
                @if($userId == $survey->creator_id OR
                    $userGroup == "admin" OR
                    $userGroup == "marketing" OR
                    $userGroup == "clubleitung")
                    <a href="{{$survey->id}}/edit"
                       style="float: right"
                       class="btn btn-default btn-sm"
                       data-placement="bottom">
                        <i class="fa fa-pencil-square-o" style="color: black"></i>
                    </a>
                @endif
            </h4>

        </div>
        <div class="panel-body">
            @if($survey->description != null)
                Beschreibung: {!! $survey->description !!}
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
                            <th>mein Club</th>
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
                        <tr id="question_row">
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
                                                <b>Mich eintragen!</b>
                                            </a>
                                        </li>
                                    </ul>
                                    {!! Form::hidden('ldapId', null, ['id' => 'ldapId']) !!}
                                @endif
                            </td>
                            <td>
                                {{--autocomplete for clubs is not working right now--}}
                                <div id="dropdown_club" class="dropdown">
                                    <div class="btn-group col-md-8 no-padding">
                                        {!! Form::text('club', null, ['class' => 'form-control', 'id' => 'club', 'placeholder' => 'mein Club', 'autocomplete' => 'off', 'required' => true, 'oninvalid' => 'setCustomValidity(\'Bist Du mitglied in einem Club?\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
                                    </div>
                                    <ul id="dropdown-menu_club" class="dropdown-menu dropdown-club"></ul>
                                </div>
                            </td>
                            @foreach($questions as $key => $question)
                                <td class="question" style="vertical-align: middle;">
                                    @if($question->field_type == 1)
                                        <!-- Freitext -->
                                        @if(!$question->is_required)
                                            <!--Answer not required-->
                                            {!! Form::text('answers['.$key.']', null, ['rows' => 2, 'class' => 'form-control', 'placeholder' => 'Antwort hier hinzufügen', 'autocomplete' => 'off']) !!}
                                        @else
                                            <!--Answer* required-->
                                            {!! Form::text('answers['.$key.']', null, ['required' => 'true', 'rows' => 2, 'class' => 'form-control', 'placeholder' => 'Antwort hier hinzufügen', 'autocomplete' => 'off', 'oninvalid' => 'setCustomValidity(\'Bitte gib eine Antwort\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
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
                                        <select class="form-control" name="answers[{{$key}}]" style="font-size: 13px;">
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

                        @if(!$survey->is_anonymous OR $userId == $survey->creator_id)
                            @if(!$survey->show_results_after_voting OR $userParticipatedAlready)
                                @foreach($answers as $key => $answer)
                                    <tr class="row{{$answer->id}}">
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
                                        <!--Edit Delete Buttons-->
                                            <td class="tdButtons panel" id="panelNoShadow">
                                                {{--not working at the moment--}}
                                                {{--<a href="#"--}}
                                                   {{--class="editButton btn btn-primary "--}}
                                                   {{--data-toggle="tooltip"--}}
                                                   {{--data-placement="bottom">--}}
                                                    {{--<i class="fa fa-pencil" id="{{$answer->id}}"></i>--}}
                                                {{--</a>--}}
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
                                {{-- shows a statistic of answers of the users who already took part in the survey--}}

                                <?php $i = 0; ?>
                                    @if(!empty(array_filter($evaluation)))
                                @foreach($answers as $key => $answer)
                                    @if($i == 0)
                                        <tr>
                                            <td class="transparent background">bg</td>
                                            <td class="transparent background">bg</td>
                                            @foreach($answer->getAnswerCells as $cell)
                                                <td class="transparent background">bg</td>
                                            @endforeach
                                        </tr>
                                        <tr id="evaluation">
                                            <td class="evaluation_heading" id="EvaluationColor">
                                                Auswertung
                                            </td>
                                            <td class="" id="EvaluationColor"></td>
                                            @foreach($evaluation as $eva_question)
                                                <td class="mobileMarginTop" id="EvaluationColor">
                                                    <div>
                                                        @foreach($eva_question as $answer_option => $counter)
                                                                @if($counter == 1)
                                                                    @if($answer_option === 'keine Angabe')
                                                                        <div>{{$counter}} Person wollte keine Angaben machen.</div>
                                                                    @else
                                                                        <div>{{$counter}} Person stimmte für: {{$answer_option}}</div>
                                                                    @endif
                                                                @else
                                                                    @if($answer_option === 'keine Angabe')
                                                                        <div>{{$counter}} Personen wollten keine Angaben machen.</div>
                                                                    @else
                                                                        <div>{{$counter}} Personen stimmten für: {{$answer_option}}</div>
                                                                    @endif
                                                                @endif
                                                        @endforeach
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endif
                                    <?php $i += 1; ?>
                                @endforeach
                                            @endif
                                <!-- End of evaluation -->
                            @endif
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


{{---------------------------------------------change-history-----------------------------------------------------}}
    @if(!$survey->is_anonymous OR $userId == $survey->creator_id)
        {{--only if the survey is public or if the user is the creator of the survey--}}
        @if(!$survey->show_results_after_voting OR $userParticipatedAlready)
            {{--only if the results are always visiable or the user has already taken part--}}
            {{--they can see the change history of the survey--}}
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
                                                {{--empty users will be marked and named guest(Gast)--}}
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
                                            {{--empty users will be marked and named guest(Gast)--}}
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
        @endif
    @endif


    <script>
        $(document).ready(function () {
            $('#surveyAnswerForm').formValidation();
        });
        $(document).ready(function () {
            $('#surveyAnswerFormMobile').formValidation();
        });
    </script>

@stop