@extends('layouts.master')

@section('title')
    Beta
@stop

@section('content')

    <div class="isotope">
        <div class="grid-sizer" style="margin-bottom: -34px;"></div>
        <div class="element-item {{ $clubEvent->getPlace->plc_title }}">
            <div class="panel">
                <div class="panel-body">     
                    @include("partials.JobsByScheduleIdSmall", [$entry, $persons, $clubs])           
                </div>
            </div>  
        </div>
    </div>
    
    <br>

    <div class="col-md-12">
        @include("partials.JobsByScheduleIdLarge", [$entry, $persons, $clubs])
    </div>


@stop