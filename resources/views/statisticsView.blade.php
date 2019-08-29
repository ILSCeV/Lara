@php
/**
* @var int $month
* @var int $year
* @var bool $isMonthStatistic
* @var \Illuminate\Support\Collection|Lara\StatisticsInformation $clubInfos
*/
@endphp
@extends('layouts.master')
@section('title')
    {{ trans('mainLang.statisticalEvaluation') }}
@stop

@section('moreScripts')
    <script src="{{asset(WebpackBuiltFiles::$jsFiles['statistics'])}}" ></script>
@endsection

@section('content')

    {{-- prev/next month --}}
    <div class="row pb-3">
        <div class="col-12 col-md-12">
            <div class="col-12 col-md-3 m-auto p-auto btn-group">
                @if($isMonthStatistic)
                    <a class="btn btn-secondary hidden-print"
                       href="{{ action('StatisticsController@showStatistics') . date("/Y/m",
                                        strtotime("previous month", mktime(0, 0, 0, $month, 1, $year))) }}">
                        &lt;&lt;
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle text-center" type="button" data-toggle="dropdown" aria-haspopup="true">
                            {{ date('F Y', mktime(0, 0, 0, $month, 1, $year))}}
                        </button>
                        <div class="dropdown-menu">
                            <table>
                                <tbody>
                                @for($i = 1; $i<13;$i+=3)
                                    <tr>
                                        @for($j = $i; $j<$i+3;$j++)
                                            @php
                                                $monthDate = date_create_from_format('Y-m',$year.'-'.$j)
                                            @endphp
                                            <td>
                                                <a class="dropdown-item" href="{{action('StatisticsController@showStatistics').$monthDate->format('/Y/m') }}">{{ $monthDate->format('F')  }}</a>
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <a class="btn btn-secondary hidden-print float-left"
                       href="{{ action('StatisticsController@showStatistics') . date("/Y/m", strtotime("next month", mktime(0, 0, 0, $month, 1, $year))) }}">
                        &gt;&gt;
                    </a>
               @else
                    <a class="btn btn-secondary hidden-print"
                       href="{{ action('StatisticsController@showYearStatistics', date("Y", strtotime("previous year", mktime(0, 0, 0, $month, 1, $year))))  }}">
                        &lt;&lt;
                    </a>

                    <span class="btn btn-lg disabled mobile-width-72-percent" style="text-align: center !important;">
                    {{ date('Y', mktime(0, 0, 0, $month, 1, $year))}}
                    </span>

                    <a class="btn btn-secondary hidden-print float-left"
                       href="{{
                        action('StatisticsController@showYearStatistics', date("Y", strtotime("next year", mktime(0, 0, 0, $month, 1, $year)))) }}">
                        &gt;&gt;
                    </a>
               @endif
            </div>
            <br class="d-block d-md-none">

            {{-- Month/year statstics selector --}}
            <div class=" col-12 btn-group float-right">
                <div class="col-12 col-md-10">
                    @if($isMonthStatistic)
                        <a class="btn btn-sm btn-primary float-right"
                           type="button"
                           href="{{ action("StatisticsController@showYearStatistics") }}">
                            {{ trans("mainLang.yearStatistic") }}
                        </a>
                    @else
                        <a class="btn btn-sm btn-primary float-right"
                           type="button"
                           href="{{ action("StatisticsController@showStatistics")  }}">
                            {{ trans("mainLang.monthStatistic") }}
                        </a>
                    @endif
                </div>
            </div>

            <br>
            <br>
            <br class="d-block d-sm-none">
        </div>
    </div>

    <div class="row container-fluid">

        {{-- Club member stats --}}
        <div class="col-12 col-md-5 p-0-xs">
            @include('partials.statisticsMembers')
        </div>

        {{-- Leaderboard --}}
        <div class="col-12 col-md-5 p-0-xs">
            <br class="d-block d-sm-none">
            @include('partials.statisticsLeaderboards')
        </div>

    </div>

    {{-- JS helpers --}}
    <script>
        var chosenMonth = {{ $month }};
        var chosenYear = {{ $year }};
        var chosenPerson;
        var isMonthStatistic = {{ $isMonthStatistic }};
        var userSection = '{{ \Auth::user()->section->title }}';
    </script>

@stop
