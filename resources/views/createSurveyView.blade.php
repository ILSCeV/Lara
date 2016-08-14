@extends('layouts.master')

@section('title')
    {{ trans('mainLang.createNewSurvey') }}
@stop

@section('content')
    <div class="row">
        {!! Form::open(array('action' => 'SurveyController@store')) !!}
            @include('partials.surveyForm', ['submitButtonText' => 'Umfrage erstellen'])&nbsp;
            <div class="form-group">
                {!! Form::submit(Lang::get('mainLang.createSurvey'), ['class'=>'btn btn-primary', 'id' => 'button-create-survey']) !!}
                &nbsp;&nbsp;&nbsp;&nbsp;<br class="visible-xs">
                <br class="visible-xs">
                <a href="javascript:history.back()" class="btn btn-default">{{ trans('mainLang.backWithoutChange') }}</a>
            </div>
        {!! Form::close() !!}
    </div>
@stop