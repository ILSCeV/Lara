<!-- Needs variables: events, date -->

@extends('layouts.master')

@section('title')
        @if(count($events)==0)
                Für {{ $date }} sind keine Veranstaltungen geplant
        @else
                {{ $date }}
        @endif
@stop

@section('content')
        
        
        @if(count($events)==0)
                <h3>Für {{ $date }} sind keine Veranstaltungen geplant</h3>
        @else
                <h3>Alle Veranstaltungen im Jahr {{ $date }}</h3>
                
                <center>{{ $events->render() }}</center>

                @foreach($events as $clubEvent)
                        @include('partials.clubEventById', $clubEvent)
                @endforeach

                <center>{{ $events->render() }}</center>
        @endif
@stop