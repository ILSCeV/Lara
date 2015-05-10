<!-- Needs variables: i, date, id -->
<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">
		<a href="{{ Request::getBasePath() }}/calendar/id/{{ $clubEvent->id }}"> 
		{{{ $clubEvent->evnt_title }}}</a></h4>
		
		{{ utf8_encode(strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start))) }} 
		&nbsp;&nbsp;&nbsp;&nbsp;
		Wann: {{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
		-
		{{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
		&nbsp;&nbsp;&nbsp;&nbsp;
		Wo: {{{ $clubEvent->getPlace->plc_title }}}
	</div>
	<div class="panel-body">
		@if( $clubEvent->getSchedule->schdl_password != '')
			<div class="well">
				Passwort:&nbsp;&nbsp; {!! Form::password('password', array('required')) !!}
			</div>
		@endif
		@if (!is_null($clubEvent->getSchedule))	
			<table class="table table-borderless table-condensed" width="100%">
				<tbody>
					@include('partials.weekJobsByScheduleId', array('entries' => $clubEvent->getSchedule->getEntries))
				</tbody>
			</table>	
		@endif
	</div>
</div>
	  

