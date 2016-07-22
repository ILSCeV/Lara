@extends('layouts.master')

@section('title')
    {{ trans('mainLang.editSurvey') }}
@stop

@section('content')
    <div class="row">
        {!! Form::model($survey, array('action' => ['SurveyController@update', $survey->id], 'method' => 'PATCH')) !!}
            @include('partials.surveyForm', ['submitButtonText' => 'Umfrage ändern'])
            &nbsp;
            <div class="form-group">
                {!! Form::submit(Lang::get('mainLang.editSurvey'), ['class'=>'btn btn-primary']) !!}
                &nbsp;&nbsp;&nbsp;&nbsp;
                <br class="visible-xs">
                <a href="javascript:history.back()" class="btn btn-default">{{ trans('mainLang.backWithoutChange') }}</a>
                &nbsp;
                <a href="/survey/{{$survey->id}}"
                   class="btn btn-default"
                   data-toggle="tooltip"
                   data-placement="bottom"
                   data-method="delete"
                   data-token="{{csrf_token()}}"
                   rel="nofollow"
                   data-confirm='{{ trans('mainLang.confirmDeleteSurvey',['title' => $survey->title]) }}'>
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        {!! Form::close() !!}
    </div>
@stop