{{-- Needs variables: persons, from, till --}}

@extends('layouts.master')

@section('title')
	Dienstestatistik
@stop

@section('content')

@if(Session::has('userId'))

	<div class="panel">
		<div class="panel-heading">
				{{ Form::open(['method' => 'POST', 'route' => ['statisticsChangeDate']]) }}
				<h4 class="panel-title">Anzahl Dienste von {{ Form::input('date', 'from', $from) }} 
													   bis {{ Form::input('date', 'till', $till) }} </h4>
				{{ Form::submit('Periode ändern', array('class'=>'btn btn-primary')) }}
				{{ Form::close() }}
			
		</div>
		<br>
		<div class="panel-body">	

			{{-- lavacharts --}}
			<div id="perf_div"></div>
			@columnchart('Dienste', 'perf_div')

<br>
<br>
{{-- edit jobtypes button --}}
    <div class="col-md-3">
        @if(Session::has('userGroup')
            AND (Session::get('userGroup') == 'marketing'
            OR Session::get('userGroup') == 'clubleitung'))
            <a href="{{ Request::getBasePath() }}/management/jobtypes" class="btn btn-default">Diensttypen verwalten</a>
        @endif
    </div>

@else
	@include('partials.accessDenied')
@endif
@stop



