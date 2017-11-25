@extends('layouts.master')

@section('title')
        @if(count($events)==0)
                {{ trans('mainLang.for') }} {{ $date }} {{ trans('mainLang.noEventsPlanned') }}
        @else
                {{ $date }}
        @endif
@stop

@section('content')

        <h3>
                <a href="/calendar/{{$previous}}" class="btn btn-default hidden-print"> << </a>
                {{ trans(count($events) == 0 ? 'mainLang.noEventsOn' : 'mainLang.EventsFor') }} {{ $date }}
                <a href="/calendar/{{$next}}" class="btn btn-default hidden-print"> >> </a>
        </h3>
        @foreach($events as $clubEvent)
                @if ($loop->first)
                        <center>{!! $events->render() !!}</center>
                @endif
                @include('partials.clubEventByIdSmall', $clubEvent)
                <br />
                @if ($loop->last)
                        <center>{!! $events->render() !!}</center>
                @endif
        @endforeach
@stop