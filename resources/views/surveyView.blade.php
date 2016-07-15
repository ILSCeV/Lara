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
            z-index: 12;
        }

        #dropdown-menu_name {
            position: absolute;
            top: 44px;
            left: 4%;
            right: 4%;
        }

        #dropdown-menu_name2 {
            position: absolute;
            left: inherit;
            top: 30px;
        }

        #cellEditing-2 {
            position: absolute;
            overflow: visible;
            z-index: 11;
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

        @media screen and (max-width: 978px) {
            {{-- we use the before element in our table implementation of the view, this can cause a bug that will cut of parts of the before element.
                 if it is too long while the table element is too short(gets sized over the table element)
                 to fix in a clean way you need to get the height of both elements and compare them for every cell and resize them depending on the difference
                 another good source on how to change pseudo elements(like the before element) is here: https://pankajparashar.com/posts/modify-pseudo-elements-css/
            --}}
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
                @if($userId == $survey->creator_id OR $userCanEditDueToRole)
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


     {!! Form::open(['action' => ['SurveyAnswerController@store', $survey->id], 'class' => 'store', 'name' => 'store']) !!}

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

                    <input hidden id="get_row" value="">
                    <input type="hidden" id="hdnSession_userName" value="{{Session::get('userName')}}">
                    <input type="hidden" id="hdnSession_userClub" value="{{Session::get('userClub')}}">
                    <input type="hidden" id="hdnSession_userID" value="{{Session::get('userId')}}">
                    <input type="hidden" id="hdnSession_oldContent" name="hidden_oldContent[]" value="">

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
                            <th></th>
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
                                        {!! Form::text('club', null, ['class' => 'form-control', 'id' => 'club', 'placeholder' => 'mein Club', 'autocomplete' => 'off', 'oninvalid' => 'setCustomValidity(\'Bist Du mitglied in einem Club?\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
                                    </div>
                                    <ul id="dropdown-menu_club" class="dropdown-menu dropdown-club"></ul>
                                </div>
                            </td>
                            @foreach($questions as $key => $question)
                                <input type="hidden" id="field_type{{$question->order-1}}" value="{{$question->field_type}}" />
                                <input type="hidden" id="question_order" value="{{$question->order}}" />
                                <input type="hidden" id="question_required{{$question->order}}" value="{{$question->is_required}}" />
                                <td class="question{{$question->order}}" style="vertical-align: middle;">
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
                                        {{ Form::radio('answers['.$key.']', 1, '' , ['id' => 'radio'.$question->order.'-0']) }} Ja
                                        @if(!$question->is_required)
                                            <!--Answer not required-->
                                            {{ Form::radio('answers['.$key.']', 0, '' , ['id' => 'radio'.$question->order.'-1']) }} Nein
                                            {{ Form::radio('answers['.$key.']', -1, true, ['id' => 'radio'.$question->order.'-2'])}} keine Angabe
                                        @else
                                            <!--Answer* required-->
                                            {{ Form::radio('answers['.$key.']', 0, true, ['id' => 'radio'.$question->order.'-1']) }} Nein
                                        @endif
                                    @elseif($question->field_type == 3)
                                        <!-- Dropdown -->
                                        <select class="form-control" id="options{{$question->order}}" name="answers[{{$key}}]" style="font-size: 13px;">
                                            @if(!$question->is_required)
                                                <option>keine Angabe</option>
                                            @endif
                                            @foreach($question->getAnswerOptions as $answerOption)
                                                <option id="option">{{$answerOption->answer_option}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </td>
                            @endforeach
                            <td class="tdButtons " id="panelNoShadow">
                                <input type="submit" class="btn btn-primary fa fa-floppy-o answer_button" id="noMarginMobile" value=""
                                        style="display: inline-block; height: 34px;">
                               {!! Form::close() !!}
                            </td>
                        </tr>
                        {!! Form::open(['action' => ['SurveyAnswerController@update', $survey->id,  'id' => '' ], 'class' => 'update']) !!}
                        @if(!$survey->is_anonymous OR $userId == $survey->creator_id)
                            @if(!$survey->show_results_after_voting OR $userParticipatedAlready)
                                @foreach($answers as $key => $answer)
                                    <tr class="row{{$answer->id}}" id="{{$answer->id}}">
                                        <td class="singleAnswer">
                                            @include('partials.surveyAnswerStatus')
                                            {{$answer->name}}
                                        </td>
                                        <td class="singleAnswer">
                                            @if(!empty($answer->club))
                                                {{$answer->club}}
                                            @else
                                                kein Club
                                            @endif
                                        </td>
                                        @foreach($answer->getAnswerCells as $cell)
                                            @if($cell->answer == null || $cell->answer == "")
                                                <td class="singleAnswer emtpyCell">
                                            @else
                                            <td class="singleAnswer">
                                            @endif
                                                {{$cell->answer}}
                                            </td>
                                        @endforeach
                                        @if($userId == $answer->creator_id OR $userCanEditDueToRole OR empty($answer->creator_id))
                                            @if($survey->deadline >= date("Y-m-d H:i:s") OR $userCanEditDueToRole)
                                            <!--Edid Delete Buttons-->
                                                <td class="tdButtons " >
                                                    <input href="#"
                                                       class="editButton btn btn-primary fa fa-pencil"
                                                       id="editButton{{$answer->id}}"
                                                       value=""
                                                       style="height: 34px; width: 43px;"
                                                       type="button"
                                                       data-toggle="tooltip"
                                                       data-placement="bottom"
                                                       onclick="change_to_submit({{$answer->id}}); get_answer_row({{$answer->id}});">
                                                    <i id="spinner{{$answer->id}}" class="fa fa-spinner fa-spin fa-2x hidden"></i>

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
                                            @endif
                                        @else
                                            <td class="tdButtons panel" id="panelNoShadow">
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach

                                {{ Form::close() }}
                                <!-- Start of evaluation -->
                                {{-- shows a statistic of answers of the users who already took part in the survey--}}

                                <?php $i = 0; ?>
                                    @if(!empty(array_filter($evaluation)))
                                @foreach($answers as $key => $answer)
                                    @if($i == 0)
                                        <tr>
                                            <td class="transparent background emtpyCell">&nbsp;</td>
                                            <td class="transparent background emtpyCell">&nbsp;</td>
                                            @foreach($answer->getAnswerCells as $cell)
                                                <td class="transparent background">&nbsp;</td>
                                            @endforeach
                                        </tr>
                                        <tr id="evaluation">
                                            <td class="evaluation_heading" id="EvaluationColor">
                                                Auswertung
                                            </td>
                                            <td class="emtpyCell    " id="EvaluationColor"></td>
                                            @foreach($evaluation as $eva_question)
                                                @if($eva_question == null)
                                                    <td class="mobileMarginTop emtpyCell" id="EvaluationColor">
                                                @else
                                                    <td class="mobileMarginTop" id="EvaluationColor">
                                                @endif
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
                                            <td class="emtpyCell" id="EvaluationColor" ></td>
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
    @if(!empty($userId))
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
                        <table class="table table-hover table-condensed">
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
                            @foreach($revisions as $key_revision => $revision)
                            <tr id="tr-header-{{$key_revision}}" onclick="toggle({{$key_revision}}, {{count($revision['revision_entries'])}})">
                                <td>{{$revision['creator_name']}}</td>
                                <td>{{$revision['summary']}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{$revision['created_at']}}</td>
                            </tr>
                                @foreach($revision['revision_entries'] as $key_entry => $entry)
                                <tr id="tr-data-{{$key_revision}}{{$key_entry}}" style="display:none">
                                    <td></td>
                                    <td></td>
                                    <td>{{$entry['changed_column_name']}}</td>
                                    <td>{{$entry['old_value']}}</td>
                                    <td>{{$entry['new_value']}}</td>
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
    @endif
@stop
