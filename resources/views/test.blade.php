@extends('layouts.master')

@section('title')
    Beta
@stop

@section('content')

    <div class="col-md-4">
        @include("partials.JobsByScheduleIdSmall", [$entry, $persons, $clubs])
    </div>
    <div class="col-md-4">&nbsp;</div>
    <div class="col-md-4">
        @include("partials.JobsByScheduleIdSmall", [$entry, $persons, $clubs])
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <div class="col-md-12">
        @include("partials.JobsByScheduleIdLarge", [$entry, $persons, $clubs])
    </div>

@stop