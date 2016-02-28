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
                <div class="panel">
                        <div class="panel-heading">
                                <h5>Keine Veranstaltungen am {{ $date }}</h5>
                        </div>
                </div>
        @else
                <h3> Veranstaltungen für {{ $date }}</h3>
                
                <center>{!! $events->render() !!}</center>

                @foreach($events as $clubEvent)
                        @include('partials.clubEventByIdSmall', $clubEvent)
                        <br />
                @endforeach

                <center>{!! $events->render() !!}</center>
        @endif
@stop