{{-- Needs variables: i, date, id --}}

@if(Session::has('userGroup')
    AND (Session::get('userGroup') == 'marketing'
    OR Session::get('userGroup') == 'clubleitung'))
    <a href="{{ Request::getBasePath() }}/
			calendar/
			create/
			{{ $date['year']}}/
			{{ $date['month'] }}/
			{{ strftime("%d", strtotime($i - $date['startDay']." day", $date['startStamp'])) }}">
	{{ date("j.", strtotime($i - $date['startDay']." day", $date['startStamp'])) }}
	</a>
@else
	{{ date("j.", strtotime($i - $date['startDay']." day", $date['startStamp'])) }}
@endif



@foreach($events as $clubEvent)
				
	{{-- sucht die Events zu den passenden Tagen --}}
	@if($clubEvent->evnt_date_start === date("Y-m-d", strtotime($i - $date['startDay']." day", $date['startStamp'])))
		<div class="{{ $clubEvent->getPlace->plc_title }}"
		@if($clubEvent->evnt_is_private)
			@if(Session::has('userId'))	
				{{-- show private events only if user is logged in --}}
				<br>- <a href="{{ Request::getBasePath() }}/calendar/id/{{ $clubEvent->id }}"> {{{ $clubEvent->evnt_title }}}</a>
			@endif
		@else 
			<br>- <a href="{{ Request::getBasePath() }}/calendar/id/{{ $clubEvent->id }}"> {{{ $clubEvent->evnt_title }}}</a>
		@endif
		</div>
	@endif
	
@endforeach 