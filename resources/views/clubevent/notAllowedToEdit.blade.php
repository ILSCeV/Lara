@extends('layouts.master')
@section('content')
    <div class="card card.bg-warning">
        <div class="card card-header">
            <h4 class="white-text">{{ trans('mainLang.noNotThisWay') }}</h4>
        </div>
        <div class="card card-body">
            @if ($creator_name == "")
                <h6>{{ trans('mainLang.onlyThe') }} <b>{{ trans('mainLang.clubManagement') }}</b> {{ trans('mainLang.orThe') }} <b>{{ trans('mainLang.marketingManager') }}</b> {{ trans('mainLang.canChangeEventJob') }}</h6>
            @else
                <h6>{{ trans('mainLang.only') }} <b>{!! $creator_name !!}</b>{{ trans('mainLang.commaThe') }} <b>{{ trans('mainLang.clubManagement') }}</b> {{ trans('mainLang.orThe') }} <b>{{ trans('mainLang.marketingManager') }}</b> {{ trans('mainLang.canChangeEventJob') }}</h6>
            @endif
        </div>
    </div>
@stop
