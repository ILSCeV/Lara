@extends('layouts.master')
@section('content')
    <div class="card text-bg-warning">
        <div class="card card-header">
            <h4>{{ __('mainLang.noNotThisWay') }}</h4>
        </div>
        <div class="card card-body">
            @if ($creator_name == "")
                <h6>{{ __('mainLang.onlyThe') }} <b>{{ __('mainLang.clubManagement') }}</b> {{ __('mainLang.orThe') }} <b>{{ __('mainLang.marketingManager') }}</b> {{ __('mainLang.canChangeEventJob') }}</h6>
            @else
                <h6>{{ __('mainLang.only') }} <b>{!! $creator_name !!}</b>{{ __('mainLang.commaThe') }} <b>{{ __('mainLang.clubManagement') }}</b> {{ __('mainLang.orThe') }} <b>{{ __('mainLang.marketingManager') }}</b> {{ __('mainLang.canChangeEventJob') }}</h6>
            @endif
        </div>
    </div>
@stop
