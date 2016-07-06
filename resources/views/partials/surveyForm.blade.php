<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="{{ asset('/js/jquery-2.1.3.min.js') }}"></script>
    <script src="{{ asset('js/surveyEdit-Create-scripts.js') }}"></script>

    <style>
        .dropdown-toggle {
            text-transform: capitalize;
        }
    </style>

</head>


<body>

<div class="panel-group">
    <div class="panel col-md-8 col-sm-12 col-xs-12">
        <h4 id="heading_create" style="display:none">Neue Umfrage erstellen:</h4>
        <h4 id="heading_edit" style="display:none">Umfrage editieren:</h4>

        <div class="panel-body">

            <div class="form-group">
                {!! Form::label('title', 'Titel:') !!}
                {!! Form::text('title', $survey->title, ['placeholder'=>'z.B. Teilnahme an der Clubfahrt',
                    'required',
                    'class' => 'form-control'
                    ]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('description', 'Beschreibung:') !!}
                {!! Form::textarea('description', $survey->description, ['size' => '100x4',
                    'class' => 'form-control'
                    ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('deadline', 'Aktiv bis:') !!}
                {!! Form:: date('deadlineDate', $date, ['class' => 'form-control'] ) !!}
                {!! Form:: time('deadlineTime', $time, ['class' => 'form-control'] ) !!}
            </div>

            <div class="form-group">
                <div>
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <label><input type="checkbox" id="required1" value="1" name="is_private" @if(Session::get('userGroup') != 'clubleitung' AND Session::get('userGroup') != 'admin' AND Session::get('userGroup') != 'marketing') checked disabled @endif class="input_checkboxitem"
                                  @if($survey->is_private) checked @endif> nur für eingeloggte Nutzer sichtbar</label>
                    <input hidden type="checkbox" id="required1_hidden" name="is_private" value="1">
                </div>
                <div>
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <label><input type="checkbox" id="required2" value="2" name="is_anonymous" class="input_checkboxitem"
                                  @if($survey->is_anonymous) checked @endif> Ergebnisse sind nur für den Umfragenersteller sichtbar </label>
                </div>
                <div>
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <label><input type="checkbox" id="required3" value="3" name="show_results_after_voting" class="input_checkboxitem"
                                  @if($survey->show_results_after_voting) checked @endif> Ergebnisse sind erst nach dem Ausfüllen sichtbar </label>
                </div>
            </div>

            <hr class="col-md-12 col-xs-12 top-padding no-margin no-padding">
            
            <div class="form-group">
                <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
                    <label for="password" class="control-label col-md-5 col-sm-5 col-xs-12">Passwort zum Eintragen:</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        {!! Form::password('password', '' ) !!}
                    </div>
                </div>

                <div class="form-groupcol-md-12 col-sm-12 col-xs-12 no-padding">
                    <label fro="passwordDouble" class="control-label col-md-5 col-sm-5 col-xs-12">Passwort wiederholen:</label>
                    <div class="col-md-7 col-sm-7 col-xs-12">
                        {!! Form::password('password_confirmation', '') !!}
                    </div>
                </div>
            </div>
            @if (!empty($survey->password))
                <div style="color: #ff9800;">
                    <small>Um das Passwort zu löschen, trage in beide Felder "delete" ein (ohne
                        Anführungszeichen).
                    </small>
                </div>
            @endif
        </div>
    </div>

    <div id="wrapper">

        <div style="visibility:hidden; display:none">
            <div id="new_passage"><table class="passage" id="new_passage" name="cloneTable">
                    <tr>
                        <td>Antwortmöglichkeit: &nbsp</td>
                        <td><textarea id="answer_option" class="form-control answer_option" type="text" name="answer_options[][]" style="height: 24px"></textarea></td>
                        <td class="helltab" rowspan="3">
                            <a href="#" id="delete_button" onclick="javascript:remove_this(this); return false;">
                                <i class="fa fa-trash" style="color:red" aria-hidden="true"></i></a>
                        </td>
                </table>
            </div>
        </div>

        <div class="questions">

            <div class="question_edit">
                <span hidden>{{$counter = 0}}</span>
                @if(isset($questions))
                    @foreach($questions as $question)

                        <div id="{{"questions" . ++$counter }}" class="clonedInput">
                            <div class="panel col-md-8 col-sm-12 col-xs-12"></div>
                            <div class="panel col-md-8 col-sm-12 col-xs-12">
                                <div class="panel-body">


                                    <div class="col-md-11 col-sm-11 col-xs-10">
                                        <h4 id="ID{{$counter}}_reference" name="reference" class="heading-reference">Frage #{{$counter}}</h4>
                                    </div>

                                    <div class="col-md-1 col-sm-1 col-xs-2">
                                        <input id="button_del_question{{$counter}}" type="button" class="btn btn-sm btn-danger fa fa-trash btnRemoveQuestion" name="button_del_question" value="&#xf1f8;">
                                    </div>


                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <fieldset>
                                            <label class="questions" for="ID{{$counter}}questions[]" name="quest_label">Frage: &nbsp</label>

                                            <textarea class="form-control" type="text" name="questions[]" id="question" style="max-width: 100%; max-height: 200px">{{ $question->question }}</textarea>
                                        </fieldset>
                                    </div>

                                    <div class="visible-xs col-xs-12">
                                        <br>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <fieldset>
                                            <select class="selectpicker" name="type_select" id="field_type{{$counter-1}}" onchange="javascript:check_question_type({{$counter-1}}); check_question_type2({{$counter-1}}); setField({{$counter-1}}); setField2({{$counter-1}});">
                                                <option value="1" data-icon="fa fa-file-text-o" @if($question->field_type === 1) selected @endif >Freitext</option>
                                                <option value="2" data-icon="fa fa-check-square-o" @if($question->field_type === 2) selected @endif >Checkbox</option>
                                                <option value="3" data-icon="fa fa-caret-square-o-down" @if($question->field_type === 3) selected @endif >Dropdown</option>
                                            </select>
                                            <input class="hidden" type="hidden" id="hiddenField{{$counter-1}}" name="type[]" value="nothingYet">
                                        </fieldset>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <fieldset class="checkbox entrylist">
                                            <label class="label_checkboxitem" for="checkboxitemitem" name="req_label"></label>
                                            <label><input type="checkbox" id="required" @if($question->is_required) checked @endif value="required" name="required[0]" class="input_checkboxitem"> erforderlich</label>
                                        </fieldset>
                                    </div>



                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="answ_option" id="answ_opt{{$counter-1}}" name="answer_options_div">
                                            @foreach($answer_options = $question->getAnswerOptions as $answer_option)
                                                <table class="passage" id="new_passage" name="cloneTable">
                                                    <tr>
                                                        <td>Antwortmöglichkeit: &nbsp</td>
                                                        <td><textarea id="answer_option" class="form-control answer_option" type="text" name="answer_options[{{$counter-1}}][]">{{ $answer_option->answer_option }}</textarea></td>
                                                        <td class="helltab" rowspan="3">
                                                            <a href="#" id="delete_button" onclick="javascript:remove_this(this); return false;">
                                                                <i class="fa fa-trash" style="color:red" aria-hidden="true"></i></a>
                                                        </td>
                                                </table>
                                            @endforeach
                                            <input class="btn btn-success btn-sm" id="button_answ{{$counter-1}}" name="btn_answ" value="Antwortmöglichkeit hinzufügen" style="display:none"  onclick="javascript:clone_this(this, 'new_passage', {{$counter-1}});" type="button">
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                    @endforeach

                        <div id="{{"questions" . ++$counter }}" class="clonedInput" data-id="clonedInput_edit" style="display: none">
                            <div class="panel col-md-8 col-sm-12 col-xs-12"></div>
                            <div class="panel col-md-8 col-sm-12 col-xs-12">
                                <div class="panel-body">


                                    <div class="col-md-11 col-sm-11 col-xs-10">
                                        <h4 id="reference" name="reference" class="heading-reference">Frage #{{$counter}}</h4>
                                    </div>

                                    <div class="col-md-1 col-sm-1 col-xs-2">
                                        <input id="button_del_question1" type="button" class="btn btn-sm btn-danger fa fa-trash btnRemoveQuestion" name="button_del_question" value="&#xf1f8;">
                                    </div>


                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <fieldset>
                                            <label class="questions" for="question" name="quest_label">Frage: &nbsp</label>

                                            <textarea class="form-control" type="text" name="questions[]" id="question" style="max-width: 100%; max-height: 300px"></textarea>
                                        </fieldset>
                                    </div>

                                    <div class="visible-xs col-xs-12">
                                        <br>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <fieldset>
                                            <select class="selectpicker" name="type_select" id="field_type" onchange="javascript:check_question_type(0); check_question_type2(0); setField(0); setField2(0);">
                                                <option value="1" class="capitalize" data-icon="fa fa-file-text-o" selected>Freitext</option>
                                                <option value="2" data-icon="fa fa-check-square-o">Checkbox</option>
                                                <option value="3" data-icon="fa fa-caret-square-o-down">Dropdown</option>
                                            </select>
                                            <input class="hidden" type="hidden" id="hiddenField" name="type[]" value="1">
                                        </fieldset>
                                    </div>


                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <fieldset class="checkbox entrylist">
                                            <label class="label_checkboxitem" for="checkboxitemitem" name="req_label"></label>
                                            <label><input type="checkbox" id="required" value="required" name="required[0]" class="input_checkboxitem"> erforderlich</label>
                                        </fieldset>
                                    </div>



                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="answ_option" id="answ_opt" name="answer_options_div">
                                            <input class="btn btn-success btn-sm" id="button_answ" name="btn_answ" value="Antwortmöglichkeit hinzufügen" style="display:none"  onclick="javascript:clone_this(this, 'new_passage', 0);" type="button">
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                @endif

                <span hidden>{{$counter2 = 0}}</span>
                @if(isset($questions))
                    @foreach($questions as $question)
                        <script>
                            check_question_type({{$counter2}}); check_question_type2({{$counter2}}); setField({{$counter2}}); setField2({{$counter2}});
                        </script>
                        <span hidden>{{++$counter2}}</span>
                    @endforeach
                @endif
            </div>

            <div class="create_survey">
                <div id="questions1" class="clonedInput">
                    <div class="panel col-md-8 col-sm-12 col-xs-12"></div>
                    <div class="panel col-md-8 col-sm-12 col-xs-12">
                        <div class="panel-body">


                            <div class="col-md-11 col-sm-11 col-xs-10">
                                <h4 id="reference" name="reference" class="heading-reference">Frage #1</h4>
                            </div>

                            <div class="col-md-1 col-sm-1 col-xs-2">
                                <input id="button_del_question1" type="button" class="btn btn-sm btn-danger fa fa-trash btnRemoveQuestion" name="button_del_question" value="&#xf1f8;">
                            </div>


                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <fieldset>
                                    <label class="questions" for="question" name="quest_label">Frage: &nbsp</label>

                                    <textarea class="form-control" type="text" name="questions[]" id="question" style="max-width: 100%; max-height: 300px"></textarea>
                                </fieldset>
                            </div>

                            <div class="visible-xs col-xs-12">
                                <br>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <fieldset>
                                    <select class="selectpicker" name="type_select" id="field_type" onchange="javascript:check_question_type(0); check_question_type2(0); setField(0); setField2(0);">
                                        <option value="1" class="capitalize" data-icon="fa fa-file-text-o" selected>Freitext</option>
                                        <option value="2" data-icon="fa fa-check-square-o">Checkbox</option>
                                        <option value="3" data-icon="fa fa-caret-square-o-down">Dropdown</option>
                                    </select>
                                    <input class="hidden" type="hidden" id="hiddenField" name="type[]" value="1">
                                </fieldset>
                            </div>


                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <fieldset class="checkbox entrylist">
                                    <label class="label_checkboxitem" for="checkboxitemitem" name="req_label"></label>
                                    <label><input type="checkbox" id="required" value="required" name="required[0]" class="input_checkboxitem"> erforderlich</label>
                                </fieldset>
                            </div>



                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="answ_option" id="answ_opt" name="answer_options_div">
                                    <input class="btn btn-success btn-sm" id="button_answ" name="btn_answ" value="Antwortmöglichkeit hinzufügen" style="display:none"  onclick="javascript:clone_this(this, 'new_passage', 0);" type="button">
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="panel col-md-8 col-sm-12 col-xs-12"></div>
        <div class="panel col-md-8 col-sm-12 col-xs-12">
            <div class="panel-body">
                <div id="addButtons">
                    <input type="button" id="btnAdd" value="Frage hinzufügen" class="btn btn-success">
                </div>
            </div>
        </div>
        @if($errors->any())
            <div class="panel col-md-8 col-sm-12 col-xs-12" style="color: #b0141a">
                <br>
                @foreach($errors->all() as $error)
                    <ul class="left-padding-16">
                        <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> {{$error}}
                    </ul>
                @endforeach
            </div>
        @endif
    </div>
</div>
</div>
</body>
</html>