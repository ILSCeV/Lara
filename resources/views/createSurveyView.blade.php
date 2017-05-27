@extends('layouts.master')

@section('title')
    {{ trans('mainLang.createNewSurvey') }}
@stop

@section('moreStylesheets')
    <style>
        .dropdown-toggle {
            text-transform: capitalize;
        }
    </style>
@stop

@section('content')
    <div class="row">
        {!! Form::open(array('action' => 'SurveyController@store')) !!}
            @include('partials.surveyForm')
        {!! Form::close() !!}
    </div>
@stop
