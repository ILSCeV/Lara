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
                <a href="{{$survey->id}}/edit"
                   style="float: right"
                   class="btn btn-default btn-sm"
                   data-placement="bottom">
                    <i class="fa fa-pencil-square-o" style="color: black"></i>
                </a>
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
                    <script>
                        function get_answer_row(number) {
                            $('#get_row').attr('value', number);
                        }
                    </script>

                    <script src="{{ asset('/js/jquery-2.1.3.min.js') }}"></script>
                    <script>
                        jQuery( document ).ready( function( $ ) {


                            var count_clicks = 0;

                            $(this).find(".fa-pencil").click(function () {

                                var counter = $(this).attr('id');
                                count_clicks++;

                                if (count_clicks === 1) {



                                    $(".row" + counter).find(".singleAnswer").attr('style', 'background-color: #B0C4DE');

                                    var i = -3;
                                    var question_counter = -3;

                                    $(".row" + counter).find(".singleAnswer").each(function () {

                                        var field_type = $('#field_type' + question_counter).val();
                                        var OriginalContent = $(this).text();
                                        var x = 0;

                                        var radio_counter = 10;

                                        i++;
                                        question_counter++;


                                        if (i == -2) {
                                            $(this).addClass("cellEditing" + i).attr('id', 'cellEditing' + i);
                                            $(this).html("<input id='newName' name='name' type='text' value='" + OriginalContent.trim() + "' />");
                                        }

                                        if (i == -1) {
                                            $(this).addClass("cellEditing" + i).attr('id', 'cellEditing' + i);
                                            $(this).html("<input id='newClub' name='club' type='text' value='" + OriginalContent.trim() + "' />");
                                        }

                                        if (i > -1 && field_type == 3) {
                                            $(this).addClass("cellEditing" + i).attr('id', 'dropdown');
                                            $(this).html("<select class='form-control' id='" + i + "' name='answers[" + question_counter +"]' style='font-size: 13px;' >");

                                            $('#options' + i).find('option').each(function () {

                                                var new_option = document.createElement("option");
                                                var options = document.createTextNode(document.getElementById('options' + i).options[x].innerHTML);
                                                new_option.appendChild(options);
                                                var dropdown = document.getElementById(""+i);
                                                dropdown.appendChild(new_option);
                                                x++;
                                                $("#" + i).attr('style', 'font-size: 13px;height: 22px;padding: 0px');
                                            });
                                        }


                                        if (i > -1 && field_type == 1) {
                                            $(this).addClass("cellEditing" + i).attr('id', 'text');
                                            $(this).html("<input id='"+i+"' name='answers[" + question_counter +"]' type='text' value='" + OriginalContent.trim() + "' />");
                                        }

                                        if (i > -1 && field_type == 2) {
                                            $(this).addClass("cellEditing" + i).attr('id', 'radio');
                                            $(this).html("");
                                            var y = 0;
                                            $('.question' + i).find('input:radio').each(function () {

                                                var new_radio = document.createElement("input");
                                                new_radio.setAttribute('type', 'radio');
                                                new_radio.setAttribute('data-id', 'radio' + i + '-' + radio_counter);
                                                new_radio.setAttribute('id', '' + i);
                                                new_radio.setAttribute('name', 'answers[' + question_counter + ']edit');
                                                var radio_text = document.createTextNode(document.getElementById('radio' + i + '-' + y).value);

                                                new_radio.setAttribute('value', document.getElementById('radio' + i + '-' + y).value);
                                                new_radio.appendChild(radio_text);
                                                var radio = document.getElementById('radio');
                                                radio.appendChild(new_radio);

                                                y++;
                                                radio_counter++;

                                            });

                                            $("input[data-id=radio" + i + "-10]").after("Ja   ");
                                            $("input[data-id=radio" + i + "-11]").after('Nein   ');
                                            $("input[data-id=radio" + i + "-12]").after('keine Angabe');
                                        }

                                    });

                                }

                                else {

                                   //  $("form").find('.update').submit();

                                    setTimeout(function(){

                                    var column_counter = -3;
                                    $('.row' + counter).find("[class^=singleAnswer]").each(function () {
                                        column_counter++;

                                        if ($(this).attr('id') == 'cellEditing-2') {
                                            var newContent = $(this).find('input').val();
                                            $(this).text(newContent);
                                        }

                                        if ($(this).attr('id') == 'cellEditing-1') {
                                            var newContent = $(this).find('input').val();
                                            $(this).text(newContent);
                                        }

                                        if ($(this).attr('id') == 'text') {
                                            var newContent = $(this).find('input').val();
                                            $(this).text(newContent);
                                        }

                                        if ($(this).attr('id') == 'radio') {
                                            if ($(this).find('input:checked').val() == 1) {
                                                var newContent = "Ja";
                                                $(this).text(newContent);
                                            }

                                            if ($(this).find('input:checked').val() == 0) {
                                                var newContent = "Nein";
                                                $(this).text(newContent);
                                            }

                                            if ($(this).find('input:checked').val() == -1) {
                                                var newContent = "keine Angabe";
                                                $(this).text(newContent);
                                            }
                                        }

                                        if ($(this).attr('id') == 'dropdown') {
                                            var skillsSelect = document.getElementById(""+column_counter);
                                            var newContent = skillsSelect.options[skillsSelect.selectedIndex].text;
                                            $(this).text(newContent);
                                        }

                                        count_clicks = 0;

                                        $(".row" + counter).find(".singleAnswer").attr('style', '');

                                      });

                                    },500);
                                }

                            });


                            $("form").find('.update').on('submit', function () {

                                var counter_ajax = $('#get_row').val();

                                var answer0 = $("#0").val();
                                var answer1 = $("#1").val();
                                var answer2 = $("#2").val();
                                var answer3 = $("#3").val();
                                var answer4 = $("#4").val();


                                $.ajax({

                                    type: $(this).prop('method'),
                                    url: $(this).prop('action'),

                                    data: JSON.stringify({

                                        // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
                                        "_token": $(document).find('input[name=_token]').val(),


                                        "name": $('.row' + counter_ajax).find("[name^=name]").val(),
                                        "club": $('.row' + counter_ajax).find("[name^=club]").val(),
                                        "ldapId": "Test",
                                        "answers": [ answer0, answer1, answer2, answer3, answer4 ],
                                        "_method": "put"

                                    }),
                                    dataType: 'json',

                                    contentType: 'application/json',

                                    complete: function () {

                                    },

                                    success: function (insertedData) {
                                     //   console.log(insertedData);
                                     //   alert(insertedData);

                                    },

                                    error: function (xhr, ajaxOptions, thrownError) {
                                        alert(JSON.stringify(xhr.responseJSON));
                                    }

                                });
                                return false;
                            });
                        });

                    </script>


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
                                <input type="hidden" id="field_type{{$question->order-1}}" value="{{$question->field_type}}" />
                                <input type="hidden" id="question_order" value="{{$question->order}}" />
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
                                            {{ Form::radio('answers['.$key.']', 0, true, ['id' => 'radio'.$question->order.'-0']) }} Nein
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
                                {{--{!! Form::submit('<i class="fa fa-pencil"></i>', ['type' => 'submit', 'class' => 'btn btn-primary btn-margin', 'style' => 'display: inline-block;', 'fo]) !!}
                                --}}
                                <button type="submit" class="btn btn-primary btn-margin" id="noMarginMobile"
                                        style="display: inline-block;"><i class="fa fa-floppy-o"></i></button>
                               {!! Form::close() !!}
                            </td>
                        </tr>
                        {!! Form::open(['action' => ['SurveyAnswerController@update', $survey->id, 'id' => $survey->id, ], 'class' => 'update']) !!}
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
                                            <td class="singleAnswer">
                                                {{$cell->answer}}
                                            </td>
                                        @endforeach
                                        @if($userId == $answer->creator_id OR $userCanEditDueToRole OR empty($answer->creator_id))
                                        <!--Edid Delete Buttons-->
                                            <td class="tdButtons panel" id="panelNoShadow">
                                                <button href="#"
                                                   class="editButton btn btn-primary "
                                                   id="editButton{{$answer->id}}"
                                                   type="button"
                                                   data-toggle="tooltip"
                                                   data-placement="bottom"
                                                   onclick="javascript: change_to_submit({{$answer->id}}); get_answer_row({{$answer->id}});">
                                                    <i class="fa fa-pencil" id="{{$answer->id}}"></i>
                                               </button>
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
                                {{ Form::close() }}
                                <!-- Start of evaluation -->

                                <?php $i = 0; ?>
                                @foreach($answers as $key => $answer)
                                    @if($i == 0)
                                        <tr>
                                            <td class="transparent background">&nbsp;</td>
                                            <td class="transparent background">&nbsp;</td>
                                            @foreach($answer->getAnswerCells as $cell)
                                                <td class="transparent background">&nbsp;</td>
                                            @endforeach
                                        </tr>
                                    @endif
                                    @if($i == 0)
                                        <tr id="evaluation">
                                            <td class="" id="EvaluationColor">
                                                Auswertung
                                            </td>
                                            <td class="" id="EvaluationColor"></td>
                                            @foreach($evaluation as $eva)
                                                <td class="mobileMarginTop" id="EvaluationColor">
                                                    <div>
                                                        @foreach($eva as $key => $evacount)
                                                                @if($evacount == 1)
                                                            <div>{{$evacount}} Person: {{$key}}</div>
                                                                @else
                                                                    <div>{{$evacount}} Personen: {{$key}}</div>
                                                                @endif
                                                        @endforeach
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endif
                                    <?php $i += 1; ?>
                                @endforeach
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
        @if(!$survey->show_results_after_voting OR $userParticipatedAlready)
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