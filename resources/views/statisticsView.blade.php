@extends('layouts.master')
@section('title')
    {{ trans('mainLang.statisticalEvaluation') }}
@stop

@section('content')
    <div class="row">
        <div class="panel col-md-6 col-sm-12 col-xs-12 no-padding">
            @include('partials.personalStatistics')
        </div>

        <div class="panel col-xs-12 col-sm-12 col-md-6 no-padding-xs">
            <div>
                @include('partials.statisticsLeaderboards')
            </div>
            <div>
                @include('partials.clubStatistics')
            </div>
        </div>
    </div>

    <script>
        var statisticInformation = {!! json_encode($infos) !!};
    </script>
@stop
