@if(count($clubEvent)==0)
	<h2>Keine Treffer</h2>
@else
<div class="panel">
	<div class="panel panel-heading">
		{{-- show only a placeholder for private events --}}
		@if($clubEvent->evnt_is_private AND !Session::has("userName"))
			<h4><span class="name">Interne Veranstaltung</span></h4>
		@else
			<h4><a href="{{ URL::route('event.show', $clubEvent->id) }}">{{{ $clubEvent->evnt_title }}}</a></h4>
		@endif
	</div>
	<div class="panel panel-body">
		<i>Beginn:</i> {{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start)) }} 
		um {{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
		<br />
		<i>Ende:</i> {{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_end)) }} 
		um {{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
		<br />
		<i>Verein:</i> {{{ $clubEvent->getPlace->plc_title }}}
	</div>
</div>
@endif