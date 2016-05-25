@extends('layouts.master')

@section('title')
    Umfrage {{$survey->title}}
@stop

@section('content')

<div class="btn-group col-md-6">
    <div class="row">
        <div class="panel no-padding">
            <h4 class="panel-title text-center">Umfrage {{ $survey->title }}</h4>
            <div class="panel-body">
                <h6>Beschreibung:</h6>
                {!! $survey->description!!}
                <div class="panel-footer">
                    Die Umfrage läuft noch bis: {{ strftime("%a, %d %b", strtotime($survey->deadline)) }} um
                    {{ date("H:i", strtotime($survey->deadline)) }}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel">
            <h5 class="panel-title text-center">Auswahlmöglichkeiten</h5>
            @foreach($questions as $question)
                <div class="row">
                    {{$question->content}}
                    {{--@foreach($answers as $answer)--}}
                        {{--<div class="col-md-3">--}}
                            {{--{{ $answer->content }}--}}
                        {{--</div>--}}
                    {{--@endforeach--}}
                </div>
            @endforeach
        </div>
    </div>

    <div>
        @include('partials.surveyAnswer')
    </div>
    <div>
        @include('partials.surveyEvaluation')
    </div>
</div>
@stop