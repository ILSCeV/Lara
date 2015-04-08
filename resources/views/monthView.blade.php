<!-- Needs variables: events, date -->

@extends('layouts.master')
@section('title')
        {{ $date['monthName'] . " " . $date['year'] }}
@stop
@section('content')

<div class="row">
<!-- create button -->
    <div class="col-md-4">
        @if(Session::has('userGroup')
            AND (Session::get('userGroup') == 'marketing'
            OR Session::get('userGroup') == 'clubleitung'))
            <a href="{{ Request::getBasePath() }}/calendar/create" class="btn btn-primary pull-left">Neue Veranstaltung erstellen</a>
        @endif
    </div>

<!-- prev/next month -->
    <div class="col-md-4">
        <ul class="pager">
            <li><a href="{{ Request::getBasePath() }}/calendar/{{ date("Y/m",strtotime("previous month", $date['startStamp'])) }}">&lt;&lt;</a></li>
            <li><h5 style="display: inline;">{{ "&nbsp;&nbsp;&nbsp;&nbsp;" . $date['monthName'] . " " . $date['year'] . "&nbsp;&nbsp;&nbsp;&nbsp;" }}</h5></li>
            <li><a href="{{ Request::getBasePath() }}/calendar/{{ date("Y/m",strtotime("next month", $date['startStamp'])) }}">&gt;&gt;</a></li>  
        </ul>
    </div>

<!-- club filtering -->
    <div class="col-md-4">
        @include('partials.filter')
    </div>
</div>

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
                        <td class="thisMonth" width=14%>
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
@stop