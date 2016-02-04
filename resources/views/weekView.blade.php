<!-- Needs variables: 'events', 'schedules',  'date', 'tasks', 'entries', 'weekStart', 'weekEnd', 'persons', 'clubs' -->

@extends('layouts.master')

@section('title')
	{{ utf8_encode(strftime("%d. %b", strtotime($weekStart))) }} - {{ utf8_encode(strftime("%d. %b", strtotime($weekEnd))) }}
@stop

@section('content')
	<div class="container">
		<!-- prev/next week -->
		<div class="btn-group col-xs-12 col-md-6">
			<a class="btn btn-default hidden-print" 
			   href="{{ Request::getBasePath() }}/calendar/{{$date['previousWeek']}}">
			   	&lt;&lt;</a>

			<span class="btn btn-lg disabled week-mo-so" style="text-align: center !important;">	
				{{ Config::get('messages_de.week-name') . $date['week']}}: 
				<br class="visible-xs hidden-print">
				{{ utf8_encode(strftime("%a %d. %B", strtotime($weekStart))) }} - 
				{{ utf8_encode(strftime("%a %d. %B", strtotime($weekEnd . '- 2 days'))) }}
			</span>

			<span class="btn btn-lg disabled week-mi-di hide" style="text-align: center !important;">	
				{{ Config::get('messages_de.week-name') . $date['week']}}: 
				<br class="visible-xs hidden-print">
				{{ utf8_encode(strftime("%a %d. %B", strtotime($weekStart . '+  2 days'))) }} - 
				{{ utf8_encode(strftime("%a %d. %B", strtotime($weekEnd))) }}
			</span>

			<a class="btn btn-default hidden-print" 
			   href="{{ Request::getBasePath() }}/calendar/{{$date['nextWeek']}}">
			   	&gt;&gt;</a>
		</div>


		<!-- filter -->
		<div class="col-xs-12 col-md-6 pull-right hidden-print">
			<br class="visible-xs hidden-print">
			@include('partials.filter')
			<button class="btn btn-xs pull-right hidden-print"  type="button" id="show-hide-time">Zeiten einblenden</button>
			<br class="hidden-xs hidden-print"><br class="visible-xs hidden-print"><br class="visible-xs hidden-print">
			<button class="btn btn-xs pull-right hidden-print"  type="button" id="change-week-view">Woche: Montag - Sonntag</button>
			<br class="visible-xs hidden-print"><br class="visible-xs hidden-print">
		</div>
	</div>

	<br class="hidden-print">
	<br class="visible-xs hidden-print">
	<br class="visible-xs hidden-print">
		
	<!-- weekdays -->
	@if (count($events)>0 OR count($tasks)>0)
		<div class="isotope">
			<!-- hack: empty day at the beginning, 
				 prevents isotope collapsing to a single column if the very first element is hidden
				 by creating an invisible block and putting it out of the way via negative margin -->
			<div class="grid-sizer" style="margin-bottom: -34px;"></div>
			<!-- end of hack -->

			@foreach($events as $clubEvent)
				@if($clubEvent->evnt_is_private)

					@if(Session::has('userId'))
						<!-- we compare the current week number with the week the event happens in
							 to catch and hide any events on mondays and tuesdays (day < 3) next week 
							 in Mo-So or alternatively mondays/tuesdays this week in Mi-Di view -->
						@if ( date('W', strtotime($clubEvent->evnt_date_start)) === $date['week'] 
						  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
							<div class="element-item private {{ $clubEvent->getPlace->plc_title }} week-mo-so">
						@elseif ( date("W", strtotime($clubEvent->evnt_date_start) )  
							  === date("W", strtotime("next Week".$weekStart))
							  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
							<div class="element-item private {{ $clubEvent->getPlace->plc_title }} week-mi-di hide">
						@else
							<div class="element-item private {{ $clubEvent->getPlace->plc_title }}">
						@endif		
							@include('partials.weekCell', $clubEvent)
						</div>
					@else
						<!-- we compare the current week number with the week the event happens in
							 to catch and hide any events on mondays and tuesdays (day < 3) next week 
							 in Mo-So or alternatively mondays/tuesdays this week in Mi-Di view -->
						@if ( date('W', strtotime($clubEvent->evnt_date_start)) === $date['week'] 
						  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
							<div class="element-item private {{ $clubEvent->getPlace->plc_title }} week-mo-so">
						@elseif ( date("W", strtotime($clubEvent->evnt_date_start) )  
							  === date("W", strtotime("next Week".$weekStart))
							  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
							<div class="element-item private {{ $clubEvent->getPlace->plc_title }} week-mi-di hide">
						@else
							<div class="element-item private {{ $clubEvent->getPlace->plc_title }}">
						@endif		
							@include('partials.weekCellPrivate', $clubEvent)
						</div>
					@endif

				@else

					<!-- we compare the current week number with the week the event happens in
						 to catch and hide any events on mondays and tuesdays (day < 3) next week 
						 in Mo-So or alternatively mondays/tuesdays this week in Mi-Di view -->
					@if ( date('W', strtotime($clubEvent->evnt_date_start)) === $date['week'] 
					  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
						<div class="element-item {{ $clubEvent->getPlace->plc_title }} week-mo-so">
					@elseif ( date("W", strtotime($clubEvent->evnt_date_start) )  
						  === date("W", strtotime("next Week".$weekStart))
						  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
						<div class="element-item {{ $clubEvent->getPlace->plc_title }} week-mi-di hide">
					@else
						<div class="element-item {{ $clubEvent->getPlace->plc_title }}">
					@endif
						@include('partials.weekCell', $clubEvent)
					</div>

				@endif
			@endforeach 

			@if(Session::has('userId'))
				@foreach($tasks as $task)
					@if ( date('W', strtotime($task->schdl_due_date)) === $date['week'] 
					  AND date('N', strtotime($task->schdl_due_date)) < 3 )
						<div class="element-item private task bc-Club bc-Café week-mo-so">
					@elseif ( date("W", strtotime($clubEvent->evnt_date_start) )  
						  === date("W", strtotime("next Week".$weekStart))
						  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
						<div class="element-item private task bc-Club bc-Café week-mi-di hide">
					@else
						<div class="element-item private task bc-Club bc-Café">
					@endif
						@include('partials.taskWeekCell', $task)
					</div>
				@endforeach 
			@endif
			
		</div>
	@else
		<br>
		<div class="panel">
			<div class="panel-heading">
				<h5>Keine Veranstaltungen diese Woche</h5>
			</div>
		</div>
	@endif

@stop



