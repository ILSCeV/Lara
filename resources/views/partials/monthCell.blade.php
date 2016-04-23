{{-- Needs variables: i, date, id --}}

@if(Session::has('userGroup')
    AND (Session::get('userGroup') == 'marketing'
    OR Session::get('userGroup') == 'clubleitung'))
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


@foreach($events as $clubEvent)
	@if($clubEvent->evnt_date_start === date("Y-m-d", strtotime($i - $date['startDay']." day", $date['startStamp'])))

		<div class="{{ $clubEvent->getPlace->plc_title }} word-break">

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

						@if ($clubEvent->getPlace->plc_title == "bc-Club")
							<div class="cal-event calendar-public-event-bc-club">
						@else
							<div class="cal-event calendar-public-event-bc-cafe">
						@endif
							@include("partials.event-marker", $clubEvent)
						 	<a href="{{ URL::route('event.show', $clubEvent->id) }}"> 
								{{{ $clubEvent->evnt_title }}}
							</a>
						</div>

					@endif

				{{-- show everything for members, but switch the color theme according to event type --}}
				@else
					
					@if ($clubEvent->getPlace->plc_title == "bc-Club")

						@if($clubEvent->evnt_is_private)
							<div class="cal-event calendar-internal-event-bc-club">
						@else
							<div class="cal-event calendar-public-event-bc-club">
						@endif	

					@else

						@if ($clubEvent->evnt_is_private)
							<div class="cal-event calendar-internal-event-bc-cafe">
						@else
							<div class="cal-event calendar-public-event-bc-cafe">
						@endif			
						
					@endif

						@include("partials.event-marker", $clubEvent)
						<a href="{{ URL::route('event.show', $clubEvent->id) }}"> 
							{{{ $clubEvent->evnt_title }}}
						</a>
					</div>

				@endif

		</div>
	@endif
	
@endforeach 

@foreach($tasks as $task)
				
	{{-- sucht die Events zu den passenden Tagen --}}
	@if($task->schdl_due_date === date("Y-m-d", strtotime($i - $date['startDay']." day", $date['startStamp'])))

		<div class="word-break">
			@if(Session::has('userId'))	
				{{-- show private events only if user is logged in --}}
				<div class="cal-event calendar-task">
					<i class="fa fa-tasks"></i>
					<a href="{{ Request::getBasePath() }}/task/id/{{ $task->id }}"> 
						{{{ $task->schdl_title }}}
					</a>
				</div>
			@endif
		</div>
	@endif
	
@endforeach 