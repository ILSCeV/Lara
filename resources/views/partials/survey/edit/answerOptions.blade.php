@if (isset($question->options) && $question->options->count() > 0)
    @foreach($question->options as $answer_id => $option)
        <div class="input-group">
            <div class="input-group-addon">{{ trans('mainLang.answerOption') }}</div>
            <input type="text" class="form-control" value="{{$option->answer_option}}" name="answerOption[{{$id}}][{{$answer_id}}]">
            <div class="input-group-addon">
                <a href="#" onclick="javascript:removeAnswerOption(this); return false;">
                    <i class="fa fa-trash" style="color:red" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    @endforeach
@else
    <div class="input-group">
        <div class="input-group-addon">{{ trans('mainLang.answerOption') }}</div>
        <input type="text" class="form-control" value="" name="answerOption[{{$id}}][0]">
        <div class="input-group-addon">
            <a href="#" onclick="javascript:removeAnswerOption(this); return false;">
                <i class="fa fa-trash" style="color:red" aria-hidden="true"></i>
            </a>
        </div>
    </div>
@endif
