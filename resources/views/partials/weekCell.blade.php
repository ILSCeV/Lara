<!-- Needs variables: i, date, id -->
<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a href="{{ Request::getBasePath() }}/calendar/id/{{ $clubEvent->id }}"> 
				<span class="name">{{{ $clubEvent->evnt_title }}}</span>
			</a>
		</h4>
		
		{{ utf8_encode(strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start))) }} 
		&nbsp;
		DV: {{ date("H:i", strtotime($clubEvent->getSchedule->schdl_time_preparation_start)) }}
		&nbsp;
		<i class="fa fa-clock-o"></i> {{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
		-
		{{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
		&nbsp;
		<i class="fa fa-map-marker">&nbsp;</i>{{{ $clubEvent->getPlace->plc_title }}}
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
        @if(Session::has('userGroup')
        AND (Session::get('userGroup') == 'marketing'
        OR Session::get('userGroup') == 'clubleitung'))
			<div class="pull-right hidden-print">
				<small><a href="#" class="hide-event">Ausblenden</a></small>
			</div>
		@endif
	</div>
</div>
	  

