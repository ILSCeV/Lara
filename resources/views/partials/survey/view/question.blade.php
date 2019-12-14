<input type="hidden" id="field_type{{$question->order-1}}"
       value="{{$question->field_type}}"/>
<input type="hidden" id="question_order" value="{{$question->order}}"/>
<input type="hidden" id="question_required{{$question->order}}"
       value="{{$question->is_required}}"/>
@switch($question->field_type)
    @case(\Lara\QuestionType::Text)

    <!-- Freitext -->
    @if(!$question->is_required)
        <!--Answer not required-->
        {!! Form::text('answers['.$key.']', null, ['rows' => 2, 'class' => 'form-control', 'placeholder' => Lang::get('mainLang.addAnswerHere'), 'autocomplete' => 'off']) !!}
    @else
        <!--Answer* required-->
        {!! Form::text('answers['.$key.']', null, ['required', 'rows' => 2, 'class' => 'form-control', 'placeholder' => Lang::get('mainLang.addAnswerHere'), 'autocomplete' => 'off', 'oninvalid' => 'setCustomValidity(\'Bitte gib eine Antwort\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
    @endif
    @break
    @case(\Lara\QuestionType::Checkbox)
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
    @break
    @case(\Lara\QuestionType::Dropdown)
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
@endswitch
