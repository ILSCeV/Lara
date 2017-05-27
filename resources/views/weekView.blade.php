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
			<div class="pull-right">
				@include('partials.filter')
				{{-- show time button Ger.: Zeiten einblenden --}}
				<button class="btn btn-xs hidden-print" type="button" id="toggle-shift-time">{{ trans('mainLang.shiftTime') }}</button> 
				
				{{-- hide taken shifts button Ger.: Vergebenen Diensten ausblenden --}}
				<button class="btn btn-xs hidden-print" type="button" id="toggle-taken-shifts">{{ trans('mainLang.hideTakenShifts') }}</button>

				{{-- show/hide all comment fields --}}
				<button class="btn btn-xs hidden-print" type="button" id="toggle-all-comments">{{ trans('mainLang.comments') }}</button>
				
				{{-- week: Monday - Sunday button Ger.: Woche: Montag - Sonntag --}}
				<button class="btn btn-xs btn-primary hidden-print" type="button" id="toggle-week-start">{{ trans('mainLang.weekStart') }}</button> 
							 
			</div>
		</div>
	</div>

	<br class="visible-xs">

	<div class="containerPadding12Mobile" >
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
								<div class="element-item private section-filter @foreach($sections as $section) {!! in_array( $section->plc_title, json_decode($clubEvent->evnt_show_to_club) ) ? $section->plc_title : false !!} @endforeach week-mo-so">
							@elseif ( date("W", strtotime($clubEvent->evnt_date_start) )
								  === date("W", strtotime("next Week".$weekStart))
								  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
								<div class="element-item private section-filter @foreach($sections as $section) {!! in_array( $section->plc_title, json_decode($clubEvent->evnt_show_to_club) ) ? $section->plc_title : false !!} @endforeach week-mi-di hide">
							@else
								<div class="element-item private section-filter @foreach($sections as $section) {!! in_array( $section->plc_title, json_decode($clubEvent->evnt_show_to_club) ) ? $section->plc_title : false !!} @endforeach">
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
								<div class="element-item section-filter @foreach($sections as $section) {!! in_array( $section->plc_title, json_decode($clubEvent->evnt_show_to_club) ) ? $section->plc_title : false !!} @endforeach week-mo-so">
							@elseif ( date("W", strtotime($clubEvent->evnt_date_start) )
								  === date("W", strtotime("next Week".$weekStart))
								  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
								<div class="element-item section-filter @foreach($sections as $section) {!! in_array( $section->plc_title, json_decode($clubEvent->evnt_show_to_club) ) ? $section->plc_title : false !!} @endforeach week-mi-di hide">
							@else
								<div class="element-item section-filter @foreach($sections as $section) {!! in_array( $section->plc_title, json_decode($clubEvent->evnt_show_to_club) ) ? $section->plc_title : false !!} @endforeach">
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
								<div class="element-item private section-filter @foreach($sections as $section) {!! in_array( $section->plc_title, json_decode($clubEvent->evnt_show_to_club) ) ? $section->plc_title : false !!} @endforeach week-mo-so">
							@elseif ( date("W", strtotime($clubEvent->evnt_date_start) )
								  === date("W", strtotime("next Week".$weekStart))
								  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
								<div class="element-item private section-filter @foreach($sections as $section) {!! in_array( $section->plc_title, json_decode($clubEvent->evnt_show_to_club) ) ? $section->plc_title : false !!} @endforeach week-mi-di hide">
							@else
								<div class="element-item private section-filter @foreach($sections as $section) {!! in_array( $section->plc_title, json_decode($clubEvent->evnt_show_to_club) ) ? $section->plc_title : false !!} @endforeach">
							@endif

						@else

							@if ( date('W', strtotime($clubEvent->evnt_date_start)) === $date['week']
							  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
								<div class="element-item section-filter @foreach($sections as $section) {!! in_array( $section->plc_title, json_decode($clubEvent->evnt_show_to_club) ) ? $section->plc_title : false !!} @endforeach week-mo-so">
							@elseif ( date("W", strtotime($clubEvent->evnt_date_start) )
								  === date("W", strtotime("next Week".$weekStart))
								  AND date('N', strtotime($clubEvent->evnt_date_start)) < 3 )
								<div class="element-item section-filter @foreach($sections as $section) {!! in_array( $section->plc_title, json_decode($clubEvent->evnt_show_to_club) ) ? $section->plc_title : false !!} @endforeach week-mi-di hide">
							@else
								<div class="element-item section-filter @foreach($sections as $section) {!! in_array( $section->plc_title, json_decode($clubEvent->evnt_show_to_club) ) ? $section->plc_title : false !!} @endforeach">
							@endif

						@endif

							@include('partials.weekCellFull')

						</div>

					@endif
				@endforeach

				@if(count($surveys)>0)
					@foreach($surveys as $survey)
						@if ( date('W', strtotime($survey->deadline)) === $date['week']
			             AND  date('N', strtotime($survey->deadline)) < 3 )
							<div class="element-item section-filter bc-Club bc-Café week-mo-so ">
						@elseif ( date("W", strtotime($survey->deadline) ) === date("W", strtotime("next Week".$weekStart))
			             AND      date('N', strtotime($survey->deadline)) < 3 )
							<div class="element-item section-filter bc-Club bc-Café week-mi-di hide">
						@else
							<div class="element-item section-filter bc-Club bc-Café">
						@endif
							@include('partials.weekCellSurvey')
						</div>
					@endforeach
				@endif

				</div>
			</div>
		</div>

		@else
			<br>
			</div>
			<div class="panel" style="margin: 16px;">
				<div class="panel-heading">
					<h5>{{ trans('mainLang.noEventsThisWeek') }}</h5>
				</div>
			</div>

			<div class="isotope" style="margin: 6px;">
				{{-- hack: empty day at the beginning,
					 prevents isotope collapsing to a single column if the very first element is hidden
					 by creating an invisible block and putting it out of the way via negative margin --}}
				<div class="grid-sizer" style="margin-bottom: -34px;"></div>
				{{-- end of hack --}}
				@if(count($surveys)>0)
					@foreach($surveys as $survey)
						@if ( date('W', strtotime($survey->deadline)) === $date['week']
			             AND  date('N', strtotime($survey->deadline)) < 3 )
							<div class="element-item section-filter bc-Club bc-Café week-mo-so ">
						@elseif ( date("W", strtotime($survey->deadline) ) === date("W", strtotime("next Week".$weekStart))
			             AND      date('N', strtotime($survey->deadline)) < 3 )
							<div class="element-item section-filter bc-Club bc-Café week-mi-di hide">
						@else
							<div class="element-item section-filter bc-Club bc-Café">
						@endif
							@include('partials.weekCellSurvey')
						</div>
					@endforeach
				@endif
			</div>

		@endif
	</div>

    <div class="col-md-12 col-xs-12">
        {{-- Legend --}}
        @include("partials.legend")

        {{-- filter hack --}}
        <span id="week-view-marker" hidden>&nbsp;</span>
    </div>

@stop



