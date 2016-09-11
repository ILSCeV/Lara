@foreach($question->options as $order => $option)
    <div class="input-group">
        <div class="input-group-addon">{{ trans('mainLang.answerOption') }}</div>
        <input type="text" class="form-control" value="{{$option->answer_option}}">
        <div class="input-group-addon">
            <a href="#" onclick="javascript:removeAnswerOption(this); return false;">
                <i class="fa fa-trash" style="color:red" aria-hidden="true"></i>
            </a>
        </div>
    </div>
@endforeach
