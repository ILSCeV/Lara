<!-- useable variables:
$survey
$questions
$questionCount
$answers
$clubs
$userId
$userGroup
$userCanEditDueToRole
$username
$ldapid
-->
@extends('layouts.master')
@section('title')
    {{$survey->title}}
@endsection
@section('moreScripts')
    <script src="{{asset(WebpackBuiltFiles::$assets['survey.js'])}}"></script>
@endsection

@section('moreStylesheets')
    <link rel="stylesheet" media="all" type="text/css" href="{{ asset(WebpackBuiltFiles::$assets['survey.css']) }}"/>
    <style>
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
@endsection

@section('content')

    <div class="card p-0">
        <div class="card-title-box">
            <h4 class="card-title">
                <i class="fa fa-bar-chart"></i>
                &nbsp;&nbsp;
                {{ $survey->title }}
                @if($userId == $survey->creator_id || $userCanEditDueToRole)
                    <a href="{{$survey->id}}/edit"
                       style="float: right"
                       class="btn btn-primary btn-sm"
                       data-bs-placement="bottom">
                        <i class="fa-solid  fa-pen-square"></i>
                    </a>
                @endif
            </h4>

        </div>
        <div class="card-body" >
            @if(!is_null($survey->description))
                <p><strong>{{ __('mainLang.description') }}</strong>: {!! $survey->description !!}</p>
            @endif
            <br>
            <strong>{{ __('mainLang.surveyDeadlineTo') }}</strong>: {{ strftime("%a, %d %b", strtotime($survey->deadline)) }} {{ __('mainLang.at') }}
            {{ date("H:i", strtotime($survey->deadline)) }}.
            <br>@if(count($answers) === 0) {{ __('mainLang.noPersonAnswered') }} @endif
            @if(count($answers) === 1) {{ __('mainLang.onePersonAnswered') }} @endif
            @if(count($answers) > 1) {{ __('mainLang.fewPersonAnswered1') }} <strong>{{count($answers)}}</strong> {{ __('mainLang.fewPersonAnswered2') }} @endif
        </div>
    </div>

    <br>
    <br>


    {!! Form::open(['method'=>'POST', 'action' => ['SurveyAnswerController@store', $survey->id], 'class' => 'store', 'name' => 'store']) !!}

    @if( $survey->password != '')
    <div class="card text-bg-warning">

            <div class="hidden-print card-header">
                {!! Form::password('password', ['class'=>'col-md-4 col-12',
                                                'id'=>'password' . $survey->id,
                                                'placeholder'=>Lang::get('mainLang.enterPasswordHere')]) !!}
                <br>
            </div>
    </div>
    @endif

    <div class="clubToQuestion">
        <div class="nameToQuestion">
            <div class="panel" id="panelNoShadow">
                <div id="survey-answer" class="table-responsive-custom">

                    <input hidden id="get_row" value="">
                    <input type="hidden" id="hdnSession_userName" value="{{ $username }}">
                    <input type="hidden" id="hdnSession_userClub" value="{{Lara\Section::current()->title}}">
                    <input type="hidden" id="hdnSession_userID" value="{{ $ldapid }}">
                    <input type="hidden" id="hdnSession_oldContent" name="hidden_oldContent[]" value="">

                    <table class="table table-striped table-bordered table-sm table-responsive-custom">
                        <thead>
                        <tr>
                            <th>{{ __('mainLang.name') }}</th>
                            <th>{{ __('mainLang.myClub') }}</th>
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
                                               onClick="document.getElementById('newName').value='{{ $username }}';
                                                       document.getElementById('club').value='{{Lara\Section::current()->title}}';
                                                       document.getElementById('ldapId').value='{{ $ldapid }}'">
                                                <b>{{ __('mainLang.addMe') }}</b>
                                            </a>
                                        </li>
                                    </ul>
                                    {!! Form::hidden('ldapId', null, ['id' => 'ldapId']) !!}
                                @endif
                            </td>
                            <td>
                                {{--autocomplete for clubs is not working right now--}}
                                <div id="dropdown_club" class="dropdown">
                                    <div class="btn-group container-fluid p-0">
                                        {!! Form::text('club', null, ['class' => 'form-control', 'id' => 'club', 'placeholder' => 'mein Club', 'autocomplete' => 'off', 'oninvalid' => 'setCustomValidity(\'Bist Du mitglied in einem Club?\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
                                    </div>
                                    <ul id="dropdown-menu_club" class="dropdown-menu dropdown-club"></ul>
                                </div>
                            </td>
                            @foreach($questions as $key => $question)
                                <td class="question{{$question->order}}" style="vertical-align: middle;">
                                @include('partials.survey.view.question', ['$question'=>$question])
                                </td>
                            @endforeach
                            <td class="tdButtons " id="panelNoShadow">
                                <input type="submit" class="btn btn-primary fa fa-floppy-o answer_button"
                                       id="noMarginMobile" value=""
                                       style="display: inline-block; height: 34px;">
                                {!! Form::close() !!}
                            </td>
                        </tr>
                        {!! Form::open(['method'=>'POST', 'action' => ['SurveyAnswerController@update', $survey->id, '' ], 'class' => 'update']) !!}
                        @if(!$survey->is_anonymous || $userId == $survey->creator_id)
                            @if(!$survey->show_results_after_voting || $userParticipatedAlready)
                                @foreach($answers as $key => $answer)
                                    <tr class="row{{$answer->id}}" id="{{$answer->id}}">
                                        <td class="singleAnswer">
                                            @include('partials.surveyAnswerStatus')
                                            @if( isset($answer->person->prsn_ldap_id) && !Auth::user())
                                                @if($answer->person->isNamePrivate() == 0)
                                                    {{$answer->name}}
                                                @else
                                                    @if(isset($answer->person->user))
                                                        {{ __($answer->person->user->section->title . '.' . $answer->person->user->status) }}
                                                    @endif
                                                @endif
                                            @else
                                            {{$answer->name}}
                                            @endif
                                        </td>
                                        <td class="singleAnswer">
                                            @if(!empty($answer->club))
                                                {{$answer->club}}
                                            @else
                                                {{ __('mainLang.noClub') }}
                                            @endif
                                        </td>
                                        @foreach($answer->getAnswerCells as $key => $cell)
                                           <td class="singleAnswer @if($cell->answer == null || $cell->answer == "")
                                                emtpyCell
                                            @else
                                                singleAnswer
                                            @endif">
                                                    @switch($cell->question->field_type)
                                                        @case(\Lara\QuestionType::Checkbox)
                                                            {{__($cell->answer)}}
                                                        @break
                                                        @default
                                                            {{$cell->answer}}
                                                    @endswitch
                                                </td>
                                        @endforeach
                                                @if($userId == $answer->creator_id || $userCanEditDueToRole || empty($answer->creator_id))
                                                    @if($survey->deadline >= date("Y-m-d H:i:s") || $userCanEditDueToRole)
                                                    <!--Edit Delete Buttons-->
                                                        <td class="tdButtons ">
                                                            <button href="#"
                                                                   class="editButton btn btn-primary editSurveyAnswerBtn"
                                                                   id="editButton{{$answer->id}}"
                                                                   value=""
                                                                   style="height: 34px; width: 43px;"
                                                                   type="button"
                                                                   data-bs-toggle="tooltip"
                                                                   data-bs-placement="bottom"
                                                                   data-token="{{csrf_token()}}"
                                                                   data-id="{{$answer->id}}"
                                                                   >
                                                                <i class="fa-solid  fa-pencil-alt"></i>
                                                            </button>
                                                            <i id="spinner{{$answer->id}}"
                                                               class="fa-solid  fa-spinner fa-spin fa-2x d-none"></i>

                                                            <a href="{{$survey->id}}/answer/{{$answer->id}}"
                                                               class="btn btn-secondary deleteRow"
                                                               data-bs-toggle="tooltip"
                                                               data-bs-placement="bottom"
                                                               data-token="{{csrf_token()}}"
                                                               name="deleteRow"
                                                               rel="nofollow"
                                                               data-confirm="{{ __('mainLang.confirmDeleteAnswer') }}">
                                                                <i class="fa-solid  fa-trash"></i>
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
        @if(!$survey->is_anonymous || $userId == $survey->creator_id)
            {{--only if the survey is public or if the user is the creator of the survey--}}
            @if(!$survey->show_results_after_voting || $userParticipatedAlready)
                {{--only if the results are always visiable or the user has already taken part--}}
                {{--they can see the change history of the survey--}}
                <br>
                <span class="d-none">&nbsp;&nbsp;</span><span>&nbsp;&nbsp;</span>
                <a id="show-hide-history" class="text-body-secondary hidden-print" href="#">
                    {{ __('mainLang.listChanges') }} &nbsp;&nbsp;<i class="fa fa-caret-right" id="arrow-icon"></i>
                </a>

                <div class="card hide" id="change-history">
                    <table class="table table-responsive table-hover table-sm">
                        <thead>
                        <tr>
                            <th>{{ __('mainLang.who') }}?</th>
                            <th>{{ __('mainLang.summary') }}</th>
                            <th>{{ __('mainLang.affectedColumn') }}</th>
                            <th>{{ __('mainLang.oldValue') }}</th>
                            <th>{{ __('mainLang.newValue') }}</th>
                            <th>{{ __('mainLang.when') }}?</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($revisions as $key_revision => $revision)
                            <tr id="tr-header-{{$key_revision}}"
                                onclick="toggle({{$key_revision}}, {{ count($revision['revision_entries']) }})">
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
@endsection
