@extends('layouts.master')

@section('title')
    @if(count($events)==0)
            {{ trans('mainLang.for') }} {{ $date }} {{ trans('mainLang.noEventsPlanned') }}
    @else
            {{ $date }}
    @endif
@stop

@section('content')

{{-- Day selector --}}
        <div class="btn-group col-xs-12 col-md-5">

            <a href="/calendar/{{$previous}}" class="btn btn-default col-md-2 col-xs-2 hidden-print"> << </a>

            <h6 class="col-md-8 col-xs-8" align="center">
                {{ trans(count($events) == 0 ? 'mainLang.noEventsOn' : 'mainLang.EventsFor') }} {{ $date }}
            </h6>

            <a href="/calendar/{{$next}}" class="btn btn-default col-md-2 col-xs-2 hidden-print"> >> </a>
        </div>

{{-- Section filter --}}
        <div class="col-xs-12 col-md-7 hidden-print pull-right">
            <br class="visible-xs">
            @include('partials.filter')
            <br class="visible-xs">

        </div>

        <div class="col-md-12 col-xs-12">
            @foreach($events as $clubEvent)
                <div class="panel-group">
                    @include('partials.clubEventByIdSmall', $clubEvent)
                </div>
            @endforeach
        </div>

        <div class="col-md-12 col-xs-12">
            {{-- Legend --}}
            @include("partials.legend")

            {{-- filter hack --}}
            <span id="day-view-marker" hidden>&nbsp;</span>
        </div>
@stop
