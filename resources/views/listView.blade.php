@extends('layouts.master')

@section('title')
        @if(count($events)==0)
                {{ trans('mainLang.for') }} {{ $date }} {{ trans('mainLang.noEventsPlanned') }}
        @else
                {{ $date }}
        @endif
@stop

@section('content')
        
        
        @if(count($events)==0)
                <h3>
                        <a href="/calendar/{{$yesterday}}" class="btn btn-default hidden-print"> << </a>
                        {{ trans('mainLang.noEventsOn') }} {{ $date }}
                        <a href="/calendar/{{$tomorrow}}" class="btn btn-default hidden-print"> >> </a>
                </h3>
        @else
                <h3>
                        <a href="/calendar/{{$yesterday}}" class="btn btn-default hidden-print"> << </a>
                        {{ trans('mainLang.EventsFor') }} {{ $date }}
                        <a href="/calendar/{{$tomorrow}}" class="btn btn-default hidden-print"> >> </a>
                </h3>

                <center>{!! $events->render() !!}</center>

                @foreach($events as $clubEvent)
                        @include('partials.clubEventByIdSmall', $clubEvent)
                        <br />
                @endforeach

                <center>{!! $events->render() !!}</center>
        @endif
@stop