@extends('layouts.master')

@section('title')
    Umfrage {{$survey->title}}
@stop

@section('content')
    <div>
        <h1>{{ $survey->title }}</h1>
    </div>
    <div>
        {{ $survey->description }}
    </div>
    <div>
        Die Umfrage läuft bis:
        {{ $survey->deadline }}
    </div>

    <div>
        Fragen:
    </div>
    @foreach($questions as $question)
        <div>
            {{$question->content}}
            @foreach($answers[$question->id] as $answer)
                <div>
                    {{ $answer->name }}:
                    {{ $answer->content }}
                </div>
            @endforeach
        </div>
    @endforeach

@stop