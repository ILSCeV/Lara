@extends('layouts.master')

@section('title')
    {{ $date['monthName'] . " " . $date['year'] }}
@stop

@section('moreScripts')
    <script src="{{asset(WebpackBuiltFiles::$jsFiles['monthview'])}}" ></script>
@stop

@section('content')

{{-- Prev/next month selector --}}
    <div class="row pb-3">
        <div class="col-12 col-md-3 m-auto p-auto btn-group">
            <a class="btn hidden-print"
               href="{{ Request::getBasePath() }}/calendar/{{ date("Y/m",
                                strtotime("previous month", $date['startStamp'])) }}">
                <i class="fas fa-chevron-left"></i>
            </a>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle text-center" type="button" data-toggle="dropdown" aria-haspopup="true">
                        <strong>{{ $date['monthName'] . " " . $date['year'] }}</strong>
                </button>
                <div class="dropdown-menu">
                    <table>
                        <tbody>
                        @for($i = 1; $i<13;$i+=3)
                            <tr>
                            @for($j = $i; $j<$i+3;$j++)
                                @php
                                    $monthDate = date_create_from_format('Y-m',$date['year'].'-'.$j)
                                @endphp
                                <td>
                                    <a class="dropdown-item" href="{{ Request::getBasePath() }}/calendar/{{ $monthDate->format('Y/m') }}">{{ $monthDate->format('F')  }}</a>
                                </td>
                            @endfor
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
            </div>
            <a class="btn hidden-print"
               href="{{ Request::getBasePath() }}/calendar/{{ date("Y/m", strtotime("next month", $date['startStamp'])) }}">
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- Section filter -->
        <div class="col-12 col-md-9 p-0 m-0 d-print-none" id="section-filter">
            @include('partials.filter')
        </div>
    </div>

    <div id="monthTableContainer" class="container-fluid">
        <div class="text-center">
            <div class="fa fa-spinner fa-spin"></div>
        </div>
    </div>

    <div class="col-12">
        {{-- Legend --}}
        @include("partials.legend")

        {{-- filter hack --}}
        <span id="month-view-marker" hidden>&nbsp;</span>
    </div>
    <script>
       var year = {{ $date['year'] }};
       var month = {{ $date['month'] }};
       var isAuthenticated = {{ Auth::hasUser() ? 'true':'false' }};
    </script>
@stop
