<div class="panel-group">
    <div class="panel col-md-8 col-sm-12 col-xs-12">
        <h4 id="heading_create" style="display:none">{{ trans('mainLang.createNewSurvey') }}:</h4>
        <h4 id="heading_edit" style="display:none">{{ trans('mainLang.editSurvey') }}:</h4>

        <div class="panel-body">

            <div class="form-group">
                {!! Form::label('title', Lang::get('mainLang.placeholderSurveyTitle')) !!}
                {!! Form::text('title', $survey->title, ['placeholder'=>Lang::get('mainLang.placeholderTitleSurvey'),
                    'required',
                    'class' => 'form-control'
                    ]) !!}
            </div>
            <div class="form-group">
                {!! Form::label('description', Lang::get('mainLang.placeholderDescription')) !!}
                {!! Form::textarea('description', $survey->description, ['size' => '100x4',
                    'class' => 'form-control'
                    ]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('deadline', Lang::get('mainLang.placeholderActiveUntil')) !!}
                {!! Form:: date('deadlineDate', $date, ['class' => 'form-control'] ) !!}
                {!! Form:: time('deadlineTime', $time, ['class' => 'form-control'] ) !!}
            </div>

            <div class="form-group">
                <div>
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <label><input type="checkbox" id="required1" value="1" name="is_private" @if(Session::get('userGroup') != 'clubleitung' AND Session::get('userGroup') != 'admin' AND Session::get('userGroup') != 'marketing') checked disabled @endif class="input_checkboxitem"
                                  @if($survey->is_private) checked @endif> {{ trans('mainLang.showOnlyForLoggedInMember') }}</label>
                    <input hidden type="checkbox" id="required1_hidden" name="is_private" value="1">
                </div>
                <div>
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <label><input type="checkbox" id="required2" value="2" name="is_anonymous" class="input_checkboxitem"
                                  @if($survey->is_anonymous) checked @endif> {{ trans('mainLang.showResultsOnlyForCreator') }} </label>
                </div>
                <div>
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <label><input type="checkbox" id="required3" value="3" name="show_results_after_voting" class="input_checkboxitem"
                                  @if($survey->show_results_after_voting) checked @endif> {{ trans('mainLang.showResultsAfterFillOut') }} </label>
                </div>
            </div>

            <hr class="col-md-12 col-xs-12 top-padding no-margin no-padding">
            
            <div class="form-group">
                <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
                    @if (empty($survey->password))
                    <div id="password_note" style="color: #BDBDBD;">
                        <small>({{ trans('mainLang.passwordSetOptional') }})</small>
                    </div>
                    @endif
                    <label for="password" class="control-label col-md-4 col-sm-5 col-xs-12" style="padding-left: 0;">{{ trans('mainLang.passwordEntry') }}:</label>
                    <div class="col-md-4 col-sm-7 col-xs-12" style="padding-left: 0;">
                        {!! Form::password('password', '' ) !!}
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12"></div>
                </div>

                <div class="form-groupcol-md-12 col-sm-12 col-xs-12 no-padding">
                    <label for="passwordDouble" class="control-label col-md-4 col-sm-5 col-xs-12" style="padding-left: 0";>{{ trans('mainLang.passwordRepeat') }}:</label>
                    <div class="col-md-4 col-sm-7 col-xs-12" style="padding-left: 0;">
                        {!! Form::password('password_confirmation', '') !!}
                    </div>
                    <div class="col-md-4 col-sm-2 col-xs-12"></div>
                </div>
            </div>


            @if (!empty($survey->password))
                <div style="color: #ff9800;">
                    <small>{{ trans('mainLang.passwordDeleteMessage') }}
                    </small>
                </div>
            @endif
        </div>
    </div>



        <div style="visibility:hidden; display:none">
            <div id="new_passage"><table class="passage" id="new_passage" name="cloneTable">
                    <tr>
                        <td>{{ trans('mainLang.answerOption') }}: &nbsp</td>
                        <td><textarea id="answer_option" class="form-control answer_option" type="text" name="answer_options[][]" style="height: 22px;margin-top: 5px;margin-bottom: 5px;"></textarea></td>
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


                                    <div class="col-md-11 col-sm-11 col-xs-10" id="test" style="padding-left: 0;">
                                        <h4 id="ID{{$counter}}_reference" name="reference" class="heading-reference">{{ trans('mainLang.question') }} #{{$counter}}</h4>
                                    </div>

                                    <div class="col-md-1 col-sm-1 col-xs-2">
                                        <input id="button_del_question{{$counter}}" type="button" class="btn btn-sm btn-danger fa fa-trash btnRemoveQuestion" name="button_del_question" value="&#xf1f8;">
                                    </div>


                                    <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 1px;">
                                        <fieldset>
                                            <label class="questions" for="ID{{$counter}}questions[]" name="quest_label">{{ trans('mainLang.question') }}: &nbsp</label>

                                            <textarea class="form-control" type="text" name="questions[]" id="question" style="max-width: 100%; max-height: 200px">{{ $question->question }}</textarea>
                                        </fieldset>
                                    </div>

                                    <div class="visible-xs col-xs-12">
                                        <br>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 28px;">
                                        <fieldset>
                                            <select class="selectpicker" name="type_select" id="field_type{{$counter-1}}" onchange="javascript:check_question_type({{$counter-1}}); check_question_type2({{$counter-1}}); setField({{$counter-1}}); setField2({{$counter-1}});">
                                                <option value="1" data-icon="fa fa-file-text-o" @if($question->field_type === 1) selected @endif >{{ trans('mainLang.freeText') }}</option>
                                                <option value="2" data-icon="fa fa-check-square-o" @if($question->field_type === 2) selected @endif >{{ trans('mainLang.checkbox') }}</option>
                                                <option value="3" data-icon="fa fa-caret-square-o-down" @if($question->field_type === 3) selected @endif >{{ trans('mainLang.dropdown') }}</option>
                                            </select>
                                            <input class="hidden" type="hidden" id="hiddenField{{$counter-1}}" name="type[]" value="nothingYet">
                                        </fieldset>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 5px;">
                                        <fieldset class="checkbox entrylist">
                                            <label class="label_checkboxitem" for="checkboxitemitem" name="req_label"></label>
                                            <label><input type="checkbox" id="required" @if($question->is_required) checked @endif value="required" name="required[{{$counter-1}}]" class="input_checkboxitem"> {{ trans('mainLang.required') }}</label>
                                        </fieldset>
                                    </div>



                                    <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 0;">
                                        <div class="answ_option" id="answ_opt{{$counter-1}}" name="answer_options_div">
                                            @foreach($answer_options = $question->getAnswerOptions as $answer_option)
                                                <table class="passage" id="new_passage" name="cloneTable">
                                                    <tr>
                                                        <td>{{ trans('mainLang.answerOption') }}: &nbsp</td>
                                                        <td><textarea id="answer_option" class="form-control answer_option" style="height: 22px;margin-top: 5px;margin-bottom: 5px;" type="text" name="answer_options[{{$counter-1}}][]">{{ $answer_option->answer_option }}</textarea></td>
                                                        <td class="helltab" rowspan="3">
                                                            <a href="#" id="delete_button" onclick="javascript:remove_this(this); return false;">
                                                                <i class="fa fa-trash" style="color:red" aria-hidden="true"></i></a>
                                                        </td>
                                                </table>
                                            @endforeach
                                            <input class="btn btn-success btn-sm" id="button_answ{{$counter-1}}" name="btn_answ" value="{{ trans('mainLang.addAnswerOption') }}" style="display:none; margin-top: 10px;"  onclick="javascript:clone_this(this, 'new_passage', {{$counter-1}});" type="button">
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


                                    <div class="col-md-11 col-sm-11 col-xs-10" style="padding-left: 0;">
                                        <h4 id="reference" name="reference" class="heading-reference">{{ trans('mainLang.question') }} #{{$counter}}</h4>
                                    </div>

                                    <div class="col-md-1 col-sm-1 col-xs-2">
                                        <input id="button_del_question1" type="button" class="btn btn-sm btn-danger fa fa-trash btnRemoveQuestion" name="button_del_question" value="&#xf1f8;">
                                    </div>


                                    <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 1px;">
                                        <fieldset>
                                            <label class="questions" for="question" name="quest_label">{{ trans('mainLang.question') }}: &nbsp</label>

                                            <textarea class="form-control" type="text" name="questions[]" id="question" style="max-width: 100%; max-height: 300px"></textarea>
                                        </fieldset>
                                    </div>

                                    <div class="visible-xs col-xs-12">
                                        <br>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 28px;">
                                        <fieldset>
                                            <select class="selectpicker" name="type_select" id="field_type" onchange="javascript:check_question_type(0); check_question_type2(0); setField(0); setField2(0);">
                                                <option value="1" class="capitalize" data-icon="fa fa-file-text-o" selected>{{ trans('mainLang.freeText') }}</option>
                                                <option value="2" data-icon="fa fa-check-square-o">{{ trans('mainLang.checkbox') }}</option>
                                                <option value="3" data-icon="fa fa-caret-square-o-down">{{ trans('mainLang.dropdown') }}</option>
                                            </select>
                                            <input class="hidden" type="hidden" id="hiddenField" name="type[]" value="1">
                                        </fieldset>
                                    </div>


                                    <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 5px;">
                                        <fieldset class="checkbox entrylist">
                                            <label class="label_checkboxitem" for="checkboxitemitem" name="req_label"></label>
                                            <label><input type="checkbox" id="required" value="required" name="required[{{$counter-1}}]" class="input_checkboxitem"> {{ trans('mainLang.required') }}</label>
                                        </fieldset>
                                    </div>



                                    <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 0;">
                                        <div class="answ_option" id="answ_opt" name="answer_options_div">
                                            <input class="btn btn-success btn-sm" id="button_answ" name="btn_answ" value="{{ trans('mainLang.addAnswerOption') }}" style="display:none; margin-top: 10px;"  onclick="javascript:clone_this(this, 'new_passage', {{$counter-1}});" type="button">
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                @endif
            </div>

            <div class="create_survey">
                <div id="questions1" class="clonedInput">
                    <div class="panel col-md-8 col-sm-12 col-xs-12"></div>
                    <div class="panel col-md-8 col-sm-12 col-xs-12">
                        <div class="panel-body">


                            <div class="col-md-11 col-sm-11 col-xs-10" style="padding-left: 0;">
                                <h4 id="reference" name="reference" class="heading-reference">{{ trans('mainLang.question') }} #1</h4>
                            </div>

                            <div class="col-md-1 col-sm-1 col-xs-2">
                                <input id="button_del_question1" type="button" class="btn btn-sm btn-danger fa fa-trash btnRemoveQuestion" name="button_del_question" value="&#xf1f8;">
                            </div>


                            <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 1px;">
                                <fieldset>
                                    <label class="questions" for="question" name="quest_label">{{ trans('mainLang.question') }}: &nbsp</label>

                                    <textarea class="form-control" type="text" name="questions[]" id="question" style="max-width: 100%; max-height: 300px"></textarea>
                                </fieldset>
                            </div>

                            <div class="visible-xs col-xs-12">
                                <br>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 28px;">
                                <fieldset>
                                    <select class="selectpicker" name="type_select" id="field_type" onchange="javascript:check_question_type(0); check_question_type2(0); setField(0); setField2(0);">
                                        <option value="1" class="capitalize" data-icon="fa fa-file-text-o" selected>{{ trans('mainLang.freeText') }}</option>
                                        <option value="2" data-icon="fa fa-check-square-o">{{ trans('mainLang.checkbox') }}</option>
                                        <option value="3" data-icon="fa fa-caret-square-o-down">{{ trans('mainLang.dropdown') }}</option>
                                    </select>
                                    <input class="hidden" type="hidden" id="hiddenField" name="type[]" value="1">
                                </fieldset>
                            </div>


                            <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 5px;">
                                <fieldset class="checkbox entrylist">
                                    <label class="label_checkboxitem" for="checkboxitemitem" name="req_label"></label>
                                    <label><input type="checkbox" id="required" value="required" name="required[0]" class="input_checkboxitem"> {{ trans('mainLang.required') }}</label>
                                </fieldset>
                            </div>



                            <div class="col-md-6 col-sm-6 col-xs-12" style="padding-left: 0;">
                                <div class="answ_option" id="answ_opt" name="answer_options_div">
                                    <input class="btn btn-success btn-sm" id="button_answ" name="btn_answ" value="{{ trans('mainLang.addAnswerOption') }}" style="display:none; margin-top: 10px;"  onclick="javascript:clone_this(this, 'new_passage', 0);" type="button">
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
                    <input type="button" id="btnAdd" value="{{ trans('mainLang.addQuestion') }}" class="btn btn-success">
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