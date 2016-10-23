{{-- shows a statistic of answers of the users who already took part in the survey--}}
<?php $i = 0; ?>
@if(!empty(array_filter($evaluation)))
    @foreach($answers as $key => $answer)
        @if($i == 0)
            <tr>
                <td class="transparent background emtpyCell">&nbsp;</td>
                <td class="transparent background emtpyCell">&nbsp;</td>
                @foreach($answer->getAnswerCells as $cell)
                    <td class="transparent background">&nbsp;</td>
                @endforeach
            </tr>
            <tr id="evaluation">
                <td class="evaluation_heading" id="EvaluationColor">
                    {{ trans('mainLang.evaluation') }}
                </td>
                <td class="emtpyCell    " id="EvaluationColor"></td>
                @foreach($evaluation as $eva_question)
                    @if($eva_question == null)
                        <td class="mobileMarginTop emtpyCell" id="EvaluationColor">
                    @else
                        <td class="mobileMarginTop" id="EvaluationColor">
                            @endif
                            <div>
                                @foreach($eva_question as $answer_option => $counter)
                                    @if($counter == 1)
                                        @if($answer_option === 'keine Angabe')
                                            <div>{{$counter}} {{ trans('mainLang.personDidNotAnswer') }}</div>
                                        @else
                                            <div>{{$counter}} {{ trans('mainLang.personAnswered') }}
                                                : {{$answer_option}}</div>
                                        @endif
                                    @else
                                        @if($answer_option === 'keine Angabe')
                                            <div>{{$counter}} {{ trans('mainLang.personsDidNotAnswer') }}</div>
                                        @else
                                            <div>{{$counter}} {{ trans('mainLang.personsAnswered') }}
                                                : {{$answer_option}}</div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        @endforeach
                        <td class="emtpyCell" id="EvaluationColor"></td>
            </tr>
        @endif
        <?php $i += 1; ?>
    @endforeach
@endif
