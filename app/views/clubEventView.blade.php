{{-- Needs variables: clubEvent, entries, persons, clubs --}}

@extends('layouts.master')

@section('title')
	@if(count($clubEvent)==0)
		Keine Veranstaltungen
	@else
		{{ $clubEvent->evnt_title }}
	@endif
@stop

@section('content')

	@if(count($clubEvent)==0)
		<h2>Keine Treffer</h2>
	@else

	<div class="row">
		<div class="container col-md-6">
			<div class="panel">
				<div class="panel-heading">
				<h4 class="panel-title">{{{ $clubEvent->evnt_title }}}</h4>
				<h5 class="panel-title">{{{ $clubEvent->evnt_subtitle }}}</h5>
				</div>
				<div class="panel-body">
					<br>
					<i>Beginn:</i> {{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start)) }} um 
								   {{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
					<br>
					<i>Ende:</i>   {{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_end)) }} um 
								   {{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
					<br>
					<i>Ort:</i> {{{ $clubEvent->getPlace->plc_title }}}
				</div>
				{{-- CRUD --}}
				@if(Session::has('userGroup')
						AND (Session::get('userGroup') == 'marketing'
						OR Session::get('userGroup') == 'clubleitung'))
						<br>
						<div>						
							<a href="{{ Request::getBasePath() }}/calendar/id/{{ $clubEvent->id }}/edit" 
							   class="btn btn-primary">Veranstaltung ändern</a>
							<a href="{{ Request::getBasePath() }}/calendar/id/{{ $clubEvent->id }}/delete" 
							   onclick="confirmation();return false;" 
							   class="btn btn-default">Veranstaltung löschen</a>
							<script type="text/javascript">
								
								function confirmation() {
									if (confirm("Willst du diese Veranstaltung wirklich löschen?")){
										window.location = "{{ Request::getBasePath() }}/calendar/id/{{ $clubEvent->id }}/delete";
									}
								}
								
							</script>
						</div>
				@endif
			</div>
		</div>

		<div class="container col-md-6">
			@if( $clubEvent->evnt_public_info != '')
			<div class="panel">
				<div class="panel-body more">				
					<h5 class="panel-title">Zusatzinfos:</h5> 
					{{ nl2br(e($clubEvent->evnt_public_info)) }}
				</div>
				<button type="button" class="moreless btn btn-primary" data-dismiss="alert">mehr/weniger anzeigen</button>
			</div>
			@endif

			@if(Session::has('userId'))
				@if($clubEvent->evnt_private_details != '') 
				<div class="panel">
					<div class="panel-body more">
						<h5 class="panel-title">Weitere Details:</h5> 
						{{ nl2br(e($clubEvent->evnt_private_details)) }}
					</div>
					<button type="button" class="moreless btn btn-primary" data-dismiss="alert">mehr/weniger anzeigen</button>
				</div>
				@endif
			@endif
		</div>

	</div>

	<br>
	
	{{ Form::model($entries, array('action' => array('ScheduleController@updateSchedule', $clubEvent->getSchedule->id))) }}
	{{ Form::submit('Änderungen speichern', array('class'=>'btn btn-success')) }}
	<div class="panel">
		<div class="panel-heading">
			<h4 class="panel-title">Dienstplan</h4>
		</div>
		<div class="card-body">
			
			@if( $clubEvent->getSchedule->schdl_password != '')
				<br>
				<div class="well">
					Eintragen nur mit gültigem Passwort:&nbsp;&nbsp; {{ Form::password('password', array('required')) }}
				</div>
			@endif

			<table class="table table-hover table-condensed">
				<thead>
					<tr>
						<th class="col-md-2">
							Dienst
						</th>
						<th class="col-md-2">
							Name
						</th>
						<th class="col-md-2">
							Verein
						</th>
						<th class="col-md-6">
							Kommentar
						</th>
					</tr>
				</thead>
				<tbody>
				@include('partials.jobsByScheduleId', $entries)
				</tbody>
			</table>
			{{ Form::close() }}
		</div>
	</div>
	@endif

@stop



