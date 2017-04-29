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

            @foreach($questions->values() as $index => $question)
              {{--if a question is set to required show a * if the user didn't fill it in--}}
               {{-- Offset of 3 for the first two columns in the table and a switch from 0-indexed to 1-indexed array --}}
                #survey-answer td:nth-of-type({{$index + 3}}):before {
                content: "{{$question->question . ($question->is_required ? '*' : '')}} ";
                float: left;
                display: inline-block;
                overflow: hidden;
                white-space: normal;
                height: 100%;
            }

        @endforeach
            }
    </style>
@stop
@section('moreScripts'){{--collection of used java script functions to clean up the code--}}
@stop
@section('content')

    <div class="panel no-padding">
        <div class="panel-title-box">
            <h4 class="panel-title">
                <i class="fa fa-bar-chart-o white-text"></i>
                &nbsp;&nbsp;
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
        <div class="panel-body" >
            @if($survey->description != null)
                {{ trans('mainLang.description') }}: {!! $survey->description !!}
            @endif
            <br>
            {{ trans('mainLang.surveyDeadlineTo') }}
            : {{ strftime("%a, %d %b", strtotime($survey->deadline)) }} {{ trans('mainLang.um') }}
            {{ date("H:i", strtotime($survey->deadline)) }}.
            <br>@if(count($answers) === 0) {{ trans('mainLang.noPersonAnswered') }} @endif
            @if(count($answers) === 1) {{ trans('mainLang.onePersonAnswered') }} @endif
            @if(count($answers) > 1) {{ trans('mainLang.fewPersonAnswered1') }} {{count($answers)}} {{ trans('mainLang.fewPersonAnswered2') }} @endif
        </div>
    </div>

    <br>
    <br>


    {!! Form::open(['action' => ['SurveyAnswerController@store', $survey->id], 'class' => 'store', 'name' => 'store']) !!}

    <div class="panel panel-warning">
        @if( $survey->password != '')
            <div class="hidden-print panel-heading">
                {!! Form::password('password', ['class'=>'col-md-4 col-xs-12 black-text',
                                                'id'=>'password' . $survey->id,
                                                'placeholder'=>Lang::get('mainLang.enterPasswordHere')]) !!}
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
                            <th>{{ trans('mainLang.name') }}</th>
                            <th>{{ trans('mainLang.myClub') }}</th>
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
                                                <b>{{ trans('mainLang.addMe') }}</b>
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
                                <input type="hidden" id="field_type{{$question->order-1}}"
                                       value="{{$question->field_type}}"/>
                                <input type="hidden" id="question_order" value="{{$question->order}}"/>
                                <input type="hidden" id="question_required{{$question->order}}"
                                       value="{{$question->is_required}}"/>
                                <td class="question{{$question->order}}" style="vertical-align: middle;">
                                @if($question->field_type == 1)
                                    <!-- Freitext -->
                                    @if(!$question->is_required)
                                        <!--Answer not required-->
                                        {!! Form::text('answers['.$key.']', null, ['rows' => 2, 'class' => 'form-control', 'placeholder' => Lang::get('mainLang.addAnswerHere'), 'autocomplete' => 'off']) !!}
                                    @else
                                        <!--Answer* required-->
                                        {!! Form::text('answers['.$key.']', null, ['required', 'rows' => 2, 'class' => 'form-control', 'placeholder' => Lang::get('mainLang.addAnswerHere'), 'autocomplete' => 'off', 'oninvalid' => 'setCustomValidity(\'Bitte gib eine Antwort\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
                                    @endif
                                @elseif($question->field_type == 2)
                                    <!-- Ja/Nein -->
                                    {{ Form::radio('answers['.$key.']', 1, '' , ['id' => 'radio'.$question->order.'-0']) }} {{ trans('mainLang.yes') }}
                                    @if(!$question->is_required)
                                        <!--Answer not required-->
                                        {{ Form::radio('answers['.$key.']', 0, '' , ['id' => 'radio'.$question->order.'-1']) }} {{ trans('mainLang.no') }}
                                        {{ Form::radio('answers['.$key.']', -1, true, ['id' => 'radio'.$question->order.'-2'])}} {{ trans('mainLang.noInformation') }}
                                    @else
                                        <!--Answer* required-->
                                        {{ Form::radio('answers['.$key.']', 0, true, ['id' => 'radio'.$question->order.'-1']) }} {{ trans('mainLang.no') }}
                                    @endif
                                @elseif($question->field_type == 3)
                                    <!-- Dropdown -->
                                        <select class="form-control" id="options{{$question->order}}"
                                                name="answers[{{$key}}]" style="font-size: 13px;">
                                            @if(!$question->is_required)
                                                <option>{{ trans('mainLang.noInformation') }}</option>
                                            @endif
                                            @foreach($question->getAnswerOptions as $answerOption)
                                                <option id="option">{{$answerOption->answer_option}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </td>
                            @endforeach
                            <td class="tdButtons " id="panelNoShadow">
                                <input type="submit" class="btn btn-primary fa fa-floppy-o answer_button"
                                       id="noMarginMobile" value=""
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
                                                {{ trans('mainLang.noClub') }}
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
                                                    <!--Edit Delete Buttons-->
                                                        <td class="tdButtons ">
                                                            <input href="#"
                                                                   class="editButton btn btn-primary fa fa-pencil"
                                                                   id="editButton{{$answer->id}}"
                                                                   value=""
                                                                   style="height: 34px; width: 43px;"
                                                                   type="button"
                                                                   data-toggle="tooltip"
                                                                   data-placement="bottom"
                                                                   onclick="change_to_submit({{$answer->id}}); get_answer_row({{$answer->id}});">
                                                            <i id="spinner{{$answer->id}}"
                                                               class="fa fa-spinner fa-spin fa-2x hidden"></i>

                                                            <a href="{{$survey->id}}/answer/{{$answer->id}}"
                                                               class="btn btn-default deleteRow"
                                                               data-toggle="tooltip"
                                                               data-placement="bottom"
                                                               data-token="{{csrf_token()}}"
                                                               name="deleteRow"
                                                               rel="nofollow"
                                                               data-confirm="{{ trans('mainLang.confirmDeleteAnswer') }}">
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
                                @include('partials.survey.view.evaluation')
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
                    {{ trans('mainLang.listChanges') }} &nbsp;&nbsp;<i class="fa fa-caret-right" id="arrow-icon"></i>
                </a>

                <div class="panel hide" id="change-history">
                    <table class="table table-responsive table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>{{ trans('mainLang.who') }}?</th>
                            <th>{{ trans('mainLang.summary') }}</th>
                            <th>{{ trans('mainLang.affectedColumn') }}</th>
                            <th>{{ trans('mainLang.oldValue') }}</th>
                            <th>{{ trans('mainLang.newValue') }}</th>
                            <th>{{ trans('mainLang.when') }}?</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($revisions as $key_revision => $revision)
                            <tr id="tr-header-{{$key_revision}}"
                                onclick="toggle({{$key_revision}}, {{count($revision['revision_entries'])}})">
                                <td>{{$revision['creator_name']}}</td>
                                <td>{{$revision['summary']}}</td>
                                <td><i id="arrow-icon{{$key_revision}}" class="fa fa-caret-right"></i></td>
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
            @endif
        @endif
    @endif
@stop
