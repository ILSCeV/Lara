<!-- Needs variables: clubEvent -->


	@if(count($clubEvent)==0)
		<h2>Keine Treffer</h2>
	@else
	<div class="panel">
		<h4 class="panel-title">{{{ $clubEvent->evnt_title }}}</h4>
		<div class="panel-body">
			<i>Beginn:</i> {{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start)) }} 
			um {{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
			<br />
			<i>Ende:</i> {{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_end)) }} 
			um {{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
			<br />
			<i>Ort:</i> {{{ $clubEvent->getPlace->plc_title }}}
			<br />
			<a href="{{ Request::getBasePath() }}/calendar/id/{{ $clubEvent->id }}">Dienstplan & Infos</a>	
		</div>
	</div>
	<br />
	@endif