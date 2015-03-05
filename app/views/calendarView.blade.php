{{-- Needs variables: events, date --}}

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
                <h3>{{ $date }}</h3>
                @foreach($events as $clubEvent)
                        @include('partials.clubEventById', $clubEvent)
                @endforeach
        @endif
@stop