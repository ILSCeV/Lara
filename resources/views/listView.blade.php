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
        <div class="btn-group col-12 col-md-5">

            <a href="/calendar/{{$previous}}" class="btn btn-secondary col-md-2 col-2 hidden-print"> << </a>

            <h6 class="col-md-8 col-8" align="center">
                {{ trans(count($events) == 0 ? 'mainLang.noEventsOn' : 'mainLang.EventsFor') }} {{ $date }}
            </h6>

            <a href="/calendar/{{$next}}" class="btn btn-secondary col-md-2 col-2 hidden-print"> >> </a>
        </div>

{{-- Section filter --}}
        <div class="col-12 col-md-7 hidden-print float-right">
            <br class="d-block d-sm-none">
            @include('partials.filter')
            <br class="d-block d-sm-none">

        </div>


            @foreach($events as $clubEvent)
                <div class="row col-md-8 col-auto">
                    @include('partials.clubEventByIdSmall', $clubEvent)
                </div>
                <div class="w-100"></div>
            @endforeach


        <div class="col-md-12 col-12">
            {{-- Legend --}}
            @include("partials.legend")

            {{-- filter hack --}}
            <span id="day-view-marker" hidden>&nbsp;</span>
        </div>
@stop
