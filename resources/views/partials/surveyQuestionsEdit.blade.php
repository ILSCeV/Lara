@if(isset($questions) && $questions->count() > 0)
    {{-- If we already have questions defined display them --}}
    @foreach($questions as $id => $question)
        @include('partials.survey.edit.question')
    @endforeach
@else
    {{-- Otherwise, display an empty question--}}
    @include('partials.survey.edit.question', ['id' => 0, 'question' => new Lara\SurveyQuestion()])
@endif
