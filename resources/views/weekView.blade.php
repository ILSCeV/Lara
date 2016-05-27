@extends('layouts.master')

@section('title')
	{{ "KW" . $date['week'] . ": " . utf8_encode(strftime("%d. %b", strtotime($weekStart))) }} - {{ utf8_encode(strftime("%d. %b", strtotime($weekEnd))) }}
@stop

@section('content')
	<div class="container-fluid no-padding">

		{{-- prev/next week --}}
		<div class="btn-group col-xs-12 col-md-6 hidden-print">
			<a class="btn btn-default hidden-print col-md-1 col-xs-2"
			   href="{{ Request::getBasePath() }}/calendar/{{$date['previousWeek']}}">
			   	&lt;&lt;</a>

			<h6 class="col-md-8 col-xs-8 week-mo-so no-margin centered">
				<br class="hidden-xs">
				{{ "KW" . $date['week']}}:
				<br class="visible-xs">
				{{ utf8_encode(strftime("%a %d. %B", strtotime($weekStart))) }} -
				<br class="visible-xs">
				{{ utf8_encode(strftime("%a %d. %B", strtotime($weekEnd . '- 2 days'))) }}
			</h6>

			<h6 class="col-md-8 col-xs-8 week-mi-di no-margin centered hide">
				<br class="hidden-xs">
				{{ "KW" . $date['week']}}:
				<br class="visible-xs">
				{{ utf8_encode(strftime("%a %d. %B", strtotime($weekStart . '+  2 days'))) }} -
				<br class="visible-xs">
				{{ utf8_encode(strftime("%a %d. %B", strtotime($weekEnd))) }}
			</h6>

			<a class="btn btn-default hidden-print col-md-1 col-xs-2"
			   href="{{ Request::getBasePath() }}/calendar/{{$date['nextWeek']}}">
			   	&gt;&gt;</a>
		</div>

		<br class="visible-xs">
		<br class="visible-xs">
		<br class="visible-xs">

		{{-- filter --}}
		<div class="col-xs-12 col-md-6 hidden-print">
			@include('partials.filter')
			<br class="hidden-xs">
			<button class="btn btn-xs hidden-print" type="button" id="show-hide-time">Zeiten einblenden</button>
			<button class="btn btn-xs hidden-print" type="button" id="change-week-view">Woche: Montag - Sonntag</button>
			<br class="visible-xs hidden-print">
		</div>
	</div>

	<br class="visible-xs">

	<div class="containerPadding12Mobile">
	{{-- weekdays --}}
	@if (count($events)>0)
		<div class="isotope">
			{{-- hack: empty day at the beginning,
				 prevents isotope collapsing to a single column if the very first element is hidden
				 by creating an invisible block and putting it out of the way via negative margin --}}
			<div class="grid-sizer" style="margin-bottom: -34px;"></div>
			{{-- end of hack --}}

			@foreach($events as $clubEvent)
				{{-- Filter: we add a css class later below if a club is mentioned in filter data --}}

				{{-- guests see private events as placeholders only, so check if user is logged in --}}
				@if(!Session::has('userId'))

					{{-- show only a placeholder for private events --}}
					@if($clubEvent->evnt_is_private)
						{{-- we compare the current week number with the week the event happens in
							 to catch and hide any events on mondays and tuesdays (day < 3) next week
							 in Mo-So or alternatively mondays/tuesdays this week in Mi-Di view --}}
						@if ( date('W', strtotime($clubEvent->evnt_date_start)) === $date['week']
						  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
							<div class="element-item private {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} 	{!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!} week-mo-so">
						@elseif ( date("W", strtotime($clubEvent->evnt_date_start) )
							  === date("W", strtotime("next Week".$weekStart))
							  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
							<div class="element-item private {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} 	{!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!} week-mi-di hide">
						@else
							<div class="element-item private {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} 	{!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!}">
						@endif
							@include('partials.weekCellHidden')
						</div>

					{{-- show public events, but protect members' entries from being changed by guests --}}
					@else

						{{-- we compare the current week number with the week the event happens in
							 to catch and hide any events on mondays and tuesdays (day < 3) next week
							 in Mo-So or alternatively mondays/tuesdays this week in Mi-Di view --}}
						@if ( date('W', strtotime($clubEvent->evnt_date_start)) === $date['week']
						  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
							<div class="element-item {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} 	{!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!} week-mo-so">
						@elseif ( date("W", strtotime($clubEvent->evnt_date_start) )
							  === date("W", strtotime("next Week".$weekStart))
							  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
							<div class="element-item {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} 	{!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!} week-mi-di hide">
						@else
							<div class="element-item {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} 	{!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!}">
						@endif
							@include('partials.weekCellProtected')
						</div>

					@endif

				{{-- show everything for members --}}
				@else

					{{-- members see both private and public events, but still need to manage color scheme --}}
					@if($clubEvent->evnt_is_private)

						{{-- we compare the current week number with the week the event happens in
							 to catch and hide any events on mondays and tuesdays (day < 3) next week
							 in Mo-So or alternatively mondays/tuesdays this week in Mi-Di view --}}
						@if ( date('W', strtotime($clubEvent->evnt_date_start)) === $date['week']
						  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
							<div class="element-item private {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} 	{!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!} week-mo-so">
						@elseif ( date("W", strtotime($clubEvent->evnt_date_start) )
							  === date("W", strtotime("next Week".$weekStart))
							  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
							<div class="element-item private {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} 	{!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!} week-mi-di hide">
						@else
							<div class="element-item private {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} 	{!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!}">
						@endif

					@else

						@if ( date('W', strtotime($clubEvent->evnt_date_start)) === $date['week']
						  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
							<div class="element-item {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} 	{!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!} week-mo-so">
						@elseif ( date("W", strtotime($clubEvent->evnt_date_start) )
							  === date("W", strtotime("next Week".$weekStart))
							  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
							<div class="element-item {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} 	{!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!} week-mi-di hide">
						@else
							<div class="element-item {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} 	{!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!}">
						@endif

					@endif

						@include('partials.weekCellFull')

					</div>

				@endif

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



	@if(count($surveys)>0)
		@foreach($surveys as $survey)
		<div>
		@if ( date('W', strtotime($survey->in_calendar)) === $date['week']
             AND date('N', strtotime($survey->in_calendar)) < 3 )
				<div class="element-item week-mo-so">
			@elseif ( date("W", strtotime($survey->in_calendar) )
                      === date("W", strtotime("next Week".$weekStart))
                    AND date('N', strtotime($survey->in_calendar)) < 3 )
					<div class="element-item week-mi-di hide">
					@else
						<div class="element-item">
	@endif

		@include('partials.weekCellSurvey')
						</div>
					</div>
				</div>
	</div>
		@endforeach


		@else
			<br>
			<div class="panel">
				<div class="panel-heading">
					<h5>Keine Umfragen diese Woche</h5>
				</div>
			</div>
	@endif
	</div>
                            </div>



{{-- Legend --}}
@include("partials.legend")

@stop



