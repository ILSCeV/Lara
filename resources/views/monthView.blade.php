<!-- Needs variables: events, date -->

@extends('layouts.master')
@section('title')
        {{ $date['monthName'] . " " . $date['year'] }}
@stop
@section('content')

    <div class="container">
        <!-- prev/next month -->
        <div class="col-xs-12 col-md-5 btn-group">

            <a class="btn btn-default hidden-print" 
               href="{{ Request::getBasePath() }}/calendar/{{ date("Y/m", 
                        strtotime("previous month", $date['startStamp'])) }}">
                &lt;&lt;
            </a>

                <span class="btn btn-lg disabled" style="text-align: center !important;">   
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
            @include('partials.create-btn')
        </div>

        <!-- filter -->
        <div class="col-xs-12 col-md-4 pull-right">
            @include('partials.filter')
        </div>
    </div>
        <br class="hidden-xs">

<!-- month table -->
<div class="panel">
    <table class="table table-bordered ">
          
        <thead>
            <tr>
                <th>{{ Config::get('messages_de.day-name-mon') }}</th>
                <th>{{ Config::get('messages_de.day-name-tue') }}</th>
                <th>{{ Config::get('messages_de.day-name-wed') }}</th>
                <th>{{ Config::get('messages_de.day-name-thu') }}</th>
                <th>{{ Config::get('messages_de.day-name-fri') }}</th>
                <th>{{ Config::get('messages_de.day-name-sat') }}</th>
                <th>{{ Config::get('messages_de.day-name-sun') }}</th>
            </tr>
        </thead>
  
        <tbody>
        @for($i = 1; $i <= $date['daysOfMonth'] + ($date['startDay'] - 1) + (7 - $date['endDay']); $i++)
               
                <!-- if monday then start new line -->
                @if(date("N", strtotime($i - $date['startDay'] . " day", $date['startStamp'])) == 1)
                        <tr>
                @endif
       
                <!-- Show table  -->
                @if($i - $date['startDay'] >= 0 AND $i-$date['startDay'] < $date['daysOfMonth'])
                        
                        @if( $date['month'] === date("m") 
                             AND strftime("%d", strtotime($i - $date['startDay']." day", $date['startStamp'])) === date("j") )
                            <td class="thisMonth today-marker" width=14%>
                        @else
                            <td class="thisMonth" width=14%>
                        @endif
                        
                                @include('partials.calendarCell', $date)
                        </td>
                @else 
                        <td class="otherMonth" width=14%>
                                <!-- date("j", strtotime($i-$date['startDay']." day", $date['startStamp'])) -->
                        </td>
                @endif
                
                <!-- if sunday then end this line -->
                @if(date("N",date("j", strtotime($i-$date['startDay']." day", $date['startStamp']))) == 7)
                        </tr>
                @endif
               
        @endfor
        </tbody>        
           
    </table>
</div>
<!-- filter hack -->
<span id="own-filter-marker" hidden>&nbsp;</span>
<!-- end filter hack -->
@stop