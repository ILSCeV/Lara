{{-- Needs variables: i, date, id --}}

{{ date("j.", strtotime($i - $date['startDay']." day", $date['startStamp'])) }}

@foreach($events as $clubEvent)
				
	{{-- sucht die Events zu den passenden Tagen --}}
	@if($clubEvent->evnt_date_start === date("Y-m-d", strtotime($i - $date['startDay']." day", $date['startStamp'])))
		@if($clubEvent->evnt_is_private)
			@if(Session::has('userId'))	
				{{-- show private events only if user is logged in --}}
				<br>- <a href="{{ Request::getBasePath() }}/calendar/id/{{ $clubEvent->id }}"> {{{ $clubEvent->evnt_title }}}</a>
			@endif
		@else 
			<br>- <a href="{{ Request::getBasePath() }}/calendar/id/{{ $clubEvent->id }}"> {{{ $clubEvent->evnt_title }}}</a>
		@endif
	@endif
	
@endforeach 