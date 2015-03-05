{{-- Needs variables: i, date, id --}}
<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title hidden-print">
		<a href="{{ Request::getBasePath() }}/calendar/id/{{ $clubEvent->id }}"> 
		{{{ $clubEvent->evnt_title }}}</a></h4>
		
		<h4 class="panel-title visible-print">
		 
		{{{ $clubEvent->evnt_title }}}</h4>
		
		
					{{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start)) }} 
					&nbsp;&nbsp;&nbsp;&nbsp;
					Wann: {{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
					-
					{{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
					&nbsp;&nbsp;&nbsp;&nbsp;
					Wo: {{{ $clubEvent->getPlace->plc_title }}}
	</div>
	<div class="panel-body">
		@if (!is_null($clubEvent->getSchedule))	
			<table class="table" width="100%">
				<tbody>
					
					@include('partials.weekJobsByScheduleId', array('entries' => $clubEvent->getSchedule->getEntries))
				</tbody>
			</table>	
		@endif
	</div>
</div>
	  

