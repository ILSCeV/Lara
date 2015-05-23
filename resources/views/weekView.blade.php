<!-- Needs variables: 'events', 'schedules',  'date', 'tasks', 'entries', 'weekStart', 'weekEnd', 'persons', 'clubs' -->

@extends('layouts.master')

@section('title')
	{{ utf8_encode(strftime("%d. %b", strtotime($weekStart))) }} - {{ utf8_encode(strftime("%d. %b", strtotime($weekEnd))) }}
@stop

@section('content')

@if(Session::has('userId'))

	<div class="container">
		<!-- prev/next week -->
		<div class="btn-group col-xs-12 col-md-6">
			<a class="btn btn-default hidden-print" 
			   href="{{ Request::getBasePath() }}/calendar/{{$date['previousWeek']}}">
			   	&lt;&lt;</a>

				<span class="btn btn-lg disabled" style="text-align: center !important;">	
					{{ Config::get('messages_de.week-name') . $date['week']}}: 
					<br class="visible-xs hidden-print">
					{{ strftime("%d. %B", strtotime($weekStart)) }} - 
					{{ utf8_encode(strftime("%d. %B", strtotime($weekEnd))) }}
				</span>

			<a class="btn btn-default hidden-print" 
			   href="{{ Request::getBasePath() }}/calendar/{{$date['nextWeek']}}">
			   	&gt;&gt;</a>
		</div>

		<!-- filter -->
		<div class="col-xs-12 col-md-6 pull-right hidden-print">
			@include('partials.filter')
			<button class="btn btn-xs pull-right hidden-print"  type="button" id="show-hide-time">Zeiten einblenden</button>
		</div>
	</div>
	

	<!-- Submit button -->
	<div class="col-xs-12 col-md-3">			
		{!! Form::open([ 'route' => ['bulkUpdate', $date['year'], $date['week'] ] ]) !!}
		@if (count($events)>0)
			{!! Form::submit('Änderungen speichern', array('class'=>'btn btn-success hidden-print')) !!}
		@endif
	</div>
	
	<br class="hidden-print">
	<br class="visible-xs hidden-print">
	<br class="visible-xs hidden-print">
			
	<!-- weekdays -->
	@if (count($events)>0)
		<div class="isotope">
			@foreach($events as $clubEvent)
				@if($clubEvent->evnt_is_private)
					@if(Session::has('userId'))
						<div class="element-item private {{ $clubEvent->getPlace->plc_title }}">
							@include('partials.weekCell', $clubEvent)
						</div>
					@endif
				@else
					<div class="element-item {{ $clubEvent->getPlace->plc_title }}">
						@include('partials.weekCell', $clubEvent)
					</div>
				@endif
			@endforeach 

			@foreach($tasks as $task)
					<div class="element-item private task bc-Club bc-Café">
						@include('partials.taskWeekCell', $task)
					</div>
			@endforeach 
			
		</div>
	@else
		<br>
		<div class="panel">
			<div class="panel-heading">
				<h5>Keine Veranstaltungen diese Woche</h5>
			</div>
		</div>
	@endif

	{!! Form::close() !!}

@else
	<!-- Access for club members only -->
	@include('partials.accessDenied')
@endif

@stop



