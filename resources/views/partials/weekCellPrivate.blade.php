<!-- Needs variables: i, date, id -->

<div class="panel panel-default">
	<div class="panel panel-heading">

		<h4 class="panel-title">
				<span class="name">Interne Veranstaltung</span>
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
			Weitere Details sind für Mitglieder <br />
			nach dem Einloggen zugänglich.
	</div>
</div>
	  

