<div name="question">
    <div class="card col-md-8 col-sm-12 col-12">
        <div class="card-body">
            <div class="col-md-11 col-sm-11 col-10 pl-0">
                <h4 class="heading-reference">{{ trans('mainLang.question') }} #</h4>
            </div>

            <div class="col-md-1 col-sm-1 col-2">
                <input name="button_del_question" type="button"
                       class="btn btn-sm btn-danger fa fa-trash btnRemoveQuestion"
                       value="&#xf1f8;">
            </div>

            <div class="col-md-6 col-sm-6 col-12 pl-0">
                <fieldset>
                    <label name="quest_label">{{ trans('mainLang.question') }}: &nbsp</label>

                    <textarea class="form-control" type="text" name="questionText[{{$id}}]"
                              style="max-width: 100%; max-height: 200px">{{ $question->question }}</textarea>
                </fieldset>
            </div>

            <div class="d-block.d-sm-none col-12">
                <br>
            </div>

            <div class="col-md-6 col-sm-6 col-12 pl-4">
                <fieldset>
                    <select class="form-control" name="type_select[{{$id}}]" onChange="javascript:updateQuestionDisplay(this)">
                        <option value="1" data-icon="fa fa-file-text-o"
                                @if($question->field_type === 1) selected @endif >{{ trans('mainLang.freeText') }}</option>
                        <option value="2" data-icon="fa fa-check-square-o"
                                @if($question->field_type === 2) selected @endif >{{ trans('mainLang.checkbox') }}</option>
                        <option value="3" data-icon="fa fa-caret-square-o-down"
                                @if($question->field_type === 3) selected @endif >{{ trans('mainLang.dropdown') }}</option>
                    </select>
                    <input class="hidden" type="hidden" name="hiddenField" value="nothingYet">
                </fieldset>
            </div>

            <div class="col-md-6 col-sm-6 col-12 pl-2">
                <fieldset class="checkbox entrylist">
                    <label class="label_checkboxitem" for="checkboxitemitem" name="req_label"></label>
                    <label><input type="checkbox" name="required[{{$id}}]" @if($question->is_required) checked
                                  @endif value="required"
                                  class="input_checkboxitem"> {{ trans('mainLang.required') }}</label>
                </fieldset>
            </div>


            <div class="col-md-6 col-sm-6 col-12 pl-0" name="answerOptionsDiv">
                <div class="answ_option">
                    @include('partials.survey.edit.answerOptions')
                    <input class="btn btn-success btn-sm" name="answerButton"
                           value="{{ trans('mainLang.addAnswerOption') }}"
                           style="margin-top: 10px;"
                           onclick="javascript:addNewAnswerOption(this);"
                           type="button">
                </div>
            </div>
        </div>
    </div>
</div>
