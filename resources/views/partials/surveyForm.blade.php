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
                    <label><input type="checkbox" id="required1" value="1" name="is_private"
                                  @if(Session::get('userGroup') != 'clubleitung' AND Session::get('userGroup') != 'admin' AND Session::get('userGroup') != 'marketing') checked
                                  disabled @endif class="input_checkboxitem"
                                  @if($survey->is_private) checked @endif> {{ trans('mainLang.showOnlyForLoggedInMember') }}
                    </label>
                    <input hidden type="checkbox" id="required1_hidden" name="is_private" value="1">
                </div>
                <div>
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <label><input type="checkbox" id="required2" value="2" name="is_anonymous"
                                  class="input_checkboxitem"
                                  @if($survey->is_anonymous) checked @endif> {{ trans('mainLang.showResultsOnlyForCreator') }}
                    </label>
                </div>
                <div>
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <label><input type="checkbox" id="required3" value="3" name="show_results_after_voting"
                                  class="input_checkboxitem"
                                  @if($survey->show_results_after_voting) checked @endif> {{ trans('mainLang.showResultsAfterFillOut') }}
                    </label>
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
                    <label for="password" class="control-label col-md-4 col-sm-5 col-xs-12"
                           style="padding-left: 0;">{{ trans('mainLang.passwordEntry') }}:</label>
                    <div class="col-md-4 col-sm-7 col-xs-12" style="padding-left: 0;">
                        {!! Form::password('password', '' ) !!}
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12"></div>
                </div>

                <div class="form-groupcol-md-12 col-sm-12 col-xs-12 no-padding">
                    <label for="passwordDouble" class="control-label col-md-4 col-sm-5 col-xs-12"
                           style="padding-left: 0" ;>{{ trans('mainLang.passwordRepeat') }}:</label>
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
        <div id="new_passage">
            <table class="passage" id="new_passage" name="cloneTable">
                <tr>
                    <td>{{ trans('mainLang.answerOption') }}: &nbsp</td>
                    <td><textarea id="answer_option" class="form-control answer_option" type="text"
                                  name="answer_options[][]"
                                  style="height: 22px;margin-top: 5px;margin-bottom: 5px;"></textarea></td>
                    <td class="helltab" rowspan="3">
                        <a href="#" id="delete_button" onclick="javascript:removeAnswerOption(this); return false;">
                            <i class="fa fa-trash" style="color:red" aria-hidden="true"></i></a>
                    </td>
            </table>
        </div>
    </div>

    <div class="questions">
        @include('partials.surveyQuestionsEdit')
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
