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
                <div class="panel">
                        <div class="panel-heading">
                                <h5>{{ trans('mainLang.noEventsOn') }} {{ $date }}</h5>
                        </div>
                </div>
        @else
                <h3> {{ trans('mainLang.EventsFor') }} {{ $date }}</h3>
                
                <center>{!! $events->render() !!}</center>

                @foreach($events as $clubEvent)
                        @include('partials.clubEventByIdSmall', $clubEvent)
                        <br />
                @endforeach

                <center>{!! $events->render() !!}</center>
        @endif
@stop