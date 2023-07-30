@extends('layouts.master')

@section('title')
    @if(count($events)==0)
            {{ __('mainLang.for') }} {{ $date }} {{ __('mainLang.noEventsPlanned') }}
    @else
            {{ $date }}
    @endif
@stop

@section('content')

{{-- Day selector --}}
        <div class="btn-group col-12 col-md-5">

            <a href="/calendar/{{$previous}}" class="btn col-md-2 col-2 hidden-print icon-link icon-link-hover">
                <i class="fa fa-chevron-left"></i>
            </a>

            <h6 class="col-md-8 col-8 text-center">
                {{ __(count($events) == 0 ? 'mainLang.noEventsOn' : 'mainLang.EventsFor') }} {{ $date }}
            </h6>

            <a href="/calendar/{{$next}}" class="btn col-md-2 col-2 hidden-print">
                <i class="fa fa-chevron-right"></i>
            </a>
        </div>

{{-- Section filter --}}
        <div class="col-12 col-md-4 hidden-print float-end">
            <br class="d-block d-sm-none">
            @include('partials.filter')
            <br class="d-block d-sm-none">

        </div>


            @foreach($events as $clubEvent)
                <div class="row mt-1 mb-2 justify-content-center">
                    <div class="col-md-8 col-auto">
                    @include('partials.clubEventByIdSmall', $clubEvent)
                    </div>
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
