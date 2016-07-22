<!-- Needs variables: events, date -->
@extends('layouts.master')
@section('title')
    {{ $date['monthName'] . " " . $date['year'] }}
@stop
@section('moreStylesheets')
<link rel="stylesheet" media="all" type="text/css" href="{{ asset('/css/monthViewResponsive.css') }}"/>
@stop
@section('content')
        <!-- prev/next month -->
<div class="col-xs-12 col-md-12">
<div class="col-xs-12 col-md-5 btn-group">
    <a class="btn btn-default hidden-print"
       href="{{ Request::getBasePath() }}/calendar/{{ date("Y/m",
                        strtotime("previous month", $date['startStamp'])) }}">
        &lt;&lt;
    </a>

                <span class="btn btn-lg disabled mobile72Width" style="text-align: center !important;">
                    {{ $date['monthName'] . " " . $date['year'] }}
                </span>
    <a class="btn btn-default hidden-print"
       href="{{ Request::getBasePath() }}/calendar/{{ date("Y/m",
                        strtotime("next month", $date['startStamp'])) }}">
        &gt;&gt;
    </a>
</div>
<!-- create button -->
<div class="col-xs-12 col-md-3">
    &nbsp;
</div>
<!-- filter -->
<div class="col-xs-12 col-md-4 pull-right">
    @include('partials.filter')
</div>
<br class="hidden-xs">
<br class="hidden-xs">
</div>

<!--Mont Table-->
<div class="col-xs-12 row bgWhite">
    <div class="col-md-12 calendarWrapper">
        <div class=" hidden-xs" id="ContentRow">
            <div class="calendarWeek noBorderTop" style="border-top: 0px">
                {{ trans('mainLang.Cw') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.Mo') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.Tu') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.We') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.Th') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.Fr') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.Sa') }}
            </div>
            <div class="weekDay padleft">
                {{ trans('mainLang.Su') }}
            </div>
        </div>
        <!--Print Weeks on left side-->
        <?php $simpleDate = 1; ?>
        @for($i = 1; $i <= $date['daysOfMonth'] + ($date['startDay'] - 1) + (7 - $date['endDay']); $i++)
                <!--define row-->
        @if($i == 1)
            <div class="calendarRow clearfix group noHeightMobile hidden-xs">
                <!--This is an empty row-->
                <!--Without this row, the first real calendar row would disapear-->
            </div>
            <div class="calendarRow clearfix group height10vh ">
                @elseif((date("N", strtotime($i - $date['startDay'] . " day", $date['startStamp'])) == 1)  && (date('W', strtotime($i - $date['startDay'] . ' day', $date['startStamp'])) === date("W")))
                    <div class="calendarRow clearfix group WeekMarkerRow" >
                @elseif( $i == 8 || $i == 15 || $i == 22 || $i == 29 || $i == 36)
                    <div class="calendarRow clearfix group ">
                        @endif
                        <!--End define row at bottom-->
                        <!-- Weeks on left side -->
                        @if(date("N", strtotime($i - $date['startDay'] . " day", $date['startStamp'])) == 1)
                        @if ( date('W', strtotime($i - $date['startDay'] . ' day', $date['startStamp'])) === date("W") )
                                <!--Current Week -->
                        <div class="calendarWeek WeekMarker">
                            @else
                                    <!--Every other week-->
                            <div class="calendarWeek">
                                @endif
                                <a href="{!! Request::getBasePath() !!}/calendar/{!! $date['year'] !!}/KW{{ date('W', strtotime($i - $date['startDay'] . ' day', $date['startStamp'])) }}">
                                    <span class="onlyOnMobile">{{ trans('mainLang.Cw') }}</span> {{ date("W", strtotime($i - $date['startDay'] . " day", $date['startStamp'])) }}
                                </a>
                            </div>
                            @endif



                            @if($i - $date['startDay'] >= 0 AND $i-$date['startDay'] < $date['daysOfMonth'])
                                @if(date("Y-m-d", strtotime($i - $date['startDay']." day", $date['startStamp'])) === date( "Y-m-d" ) )
                                    <div class="thisMonth today-marker-today custom-md-85">
                                        <!--The actual date of today marked in dark gray-->
                                        @elseif ( date('W', strtotime($i - $date['startDay'] . ' day', $date['startStamp'])) === date("W") )
                                                <!--The current week marked in light gray-->
                                        <div class="thisMonth today-marker custom-md-85">
                                            @else
                                                    <!--All regular days in this month, white-->
                                            <div class="thisMonth custom-md-85">
                                                @endif
                                                <div class="cell10 padleft">
                                                    @if(Session::has('userGroup'))
                                                        <a href="{{ Request::getBasePath() }}/
			event/
			{{ $date['year']}}/
			{{ $date['month'] }}/
			{{ strftime("%d", strtotime($i - $date['startDay']." day", $date['startStamp'])) }}/
			0/
			create">
                                                            {{$simpleDate}}
                                                        </a>
                                                    @else
                                                        {{$simpleDate}}
                                                    @endif

                                                    <?php $simpleDate++; ?>
                                                </div>
                                                <div class="cell90">
                                                    @include( 'partials.monthCell', $date)
                                                </div>
                                            </div>
                                            @else
                                                    <!--Days not in this month, no color-->
                                            <div class="otherMonth custom-md-85 empty">
                                            </div>
                                            @endif
                                                    <!-- if sunday then end this line -->
                                            @if(date("N",date("j", strtotime($i-$date['startDay']." day", $date['startStamp']))) == 7)

                                            @endif

                                            @if($i == 7 || $i == 14 || $i == 21 || $i == 28 || $i == 35 || $i == 42)
                                        </div>
                                        @endif

                                        @endfor
                                    </div>

                        </div>

                    </div>

            </div>
    </div>
</div>
</div>

        <div class="col-md-12 col-xs-12">
        {{-- Legend --}}
        @include("partials.legend")

        {{-- filter hack --}}
        <span id="own-filter-marker" hidden>&nbsp;</span>
        </div>
@stop