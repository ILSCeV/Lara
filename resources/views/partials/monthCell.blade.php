{{-- Needs variables: i, date, id --}}

@if(Session::has('userGroup'))
    <a href="{{ Request::getBasePath() }}/
			event/
			{{ $date['year']}}/
			{{ $date['month'] }}/
			{{ strftime("%d", strtotime($i - $date['startDay']." day", $date['startStamp'])) }}/
			0/
			create">
	{{ date("j.", strtotime($i - $date['startDay']." day", $date['startStamp'])) }}
	</a>
@else
	{{ date("j.", strtotime($i - $date['startDay']." day", $date['startStamp'])) }}
@endif



@foreach($surveys as $Survey)
	@if($Survey->in_calendar === date("Y-m-d", strtotime($i - $date['startDay']." day", $date['startStamp'])))
		<div class="cal-event dark-grey calendar-internal-info">

		<i class="fa fa-bar-chart-o white-text"></i>
            <a href="{{ URL::route('survey.show', $Survey->id) }}">
             <span  class="white-text"></span> {{ $Survey->title }} </span>
            </a>
		</div>

	@endif
@endforeach




@foreach($events as $clubEvent)
	@if($clubEvent->evnt_date_start === date("Y-m-d", strtotime($i - $date['startDay']." day", $date['startStamp'])))

		{{-- Filter --}}
        @if ( empty($clubEvent->evnt_show_to_club) )
        	{{-- Workaround for older events: if filter is empty - use event club data instead --}}

            <div class="filter {!! $clubEvent->getPlace->plc_title !!}  word-break">
       	@else
       		{{-- Normal scenario: add a css class accordnig to filter data --}}
			<div class="filter {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} {!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!} word-break">
		@endif
				{{-- guests see private events as placeholders only, so check if user is logged in --}}
				@if(!Session::has('userId'))
					
					{{-- show only a placeholder for private events --}}
					@if($clubEvent->evnt_is_private)

						<div class="cal-event dark-grey">
							<i class="fa fa-eye-slash white-text"></i>
							<span class="white-text">Internes Event</span>	
						</div>

					{{-- show everything for public events --}}
					@else
						@if     ($clubEvent->evnt_type == 1)
							<div class="cal-event calendar-public-info">
						@elseif ($clubEvent->evnt_type == 6 OR $clubEvent->evnt_type == 9)
							<div class="cal-event calendar-public-task">
						@elseif ($clubEvent->evnt_type == 7 OR $clubEvent->evnt_type == 8)
							<div class="cal-event calendar-public-marketing">
						@elseif ($clubEvent->getPlace->id == 1)
							<div class="cal-event calendar-public-event-bc-club">
						@elseif ($clubEvent->getPlace->id == 2)
							<div class="cal-event calendar-public-event-bc-cafe">
						@endif
							@include("partials.event-marker", $clubEvent)
						 	<a href="{{ URL::route('event.show', $clubEvent->id) }}"> 
								{{ $clubEvent->evnt_title }}
							</a>
						</div>

					@endif

				{{-- show everything for members, but switch the color theme according to event type --}}
				@else

					@if($clubEvent->evnt_is_private)
						@if     ($clubEvent->evnt_type == 1)
							<div class="cal-event calendar-internal-info">
						@elseif ($clubEvent->evnt_type == 6 OR $clubEvent->evnt_type == 9)
							<div class="cal-event calendar-internal-task">
						@elseif ($clubEvent->evnt_type == 7 OR $clubEvent->evnt_type == 8)
							<div class="cal-event calendar-internal-marketing">
						@elseif ($clubEvent->getPlace->id == 1)
							<div class="cal-event calendar-internal-event-bc-club">
						@elseif ($clubEvent->getPlace->id == 2)
							<div class="cal-event calendar-internal-event-bc-cafe">
						@else
							{{-- DEFAULT --}}
							<div class="cal-event dark-grey">
						@endif
					@else
						@if     ($clubEvent->evnt_type == 1)
							<div class="cal-event calendar-public-info">
						@elseif ($clubEvent->evnt_type == 6 OR $clubEvent->evnt_type == 9)
							<div class="cal-event calendar-public-task">
						@elseif ($clubEvent->evnt_type == 7 OR $clubEvent->evnt_type == 8)
							<div class="cal-event calendar-public-marketing">
						@elseif ($clubEvent->getPlace->id == 1)
							<div class="cal-event calendar-public-event-bc-club">
						@elseif ($clubEvent->getPlace->id == 2)
							<div class="cal-event calendar-public-event-bc-cafe">
						@else
							{{-- DEFAULT --}}
							<div class="cal-event dark-grey">
						@endif
					@endif

						@include("partials.event-marker", $clubEvent)
						<a href="{{ URL::route('event.show', $clubEvent->id) }}"> 
							{{ $clubEvent->evnt_title }}
						</a>
					</div>

				@endif

		</div>
	@endif

@endforeach






