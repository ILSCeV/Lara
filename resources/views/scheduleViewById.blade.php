<!-- Need variables: entries, schedule, persons, clubs -->

@extends('layouts.master')

@section('title')
	Dienstplan ID: {{ $schedule->id }}
@stop

@section('content')

<!-- CRUD -->
@if(Session::has('userGroup'))
	<br>
	<div class=" pull-right">						
		<a href="{{ Request::getBasePath() }}/task/id/{{ $schedule->id }}/edit" 
		   class="btn btn-primary">Aufgabe ändern</a>
		
		<span class="visible-xs hidden-md">&nbsp;</span>

		<a href="{{ Request::getBasePath() }}/task/id/{{ $schedule->id }}/delete" 
		   onclick="confirmation();return false;" 
		   class="btn btn-default">Aufgabe löschen</a>
		<script type="text/javascript">
			
			function confirmation() {
				if (confirm("Willst du diese Aufgabe wirklich löschen?")){
					window.location = "{{ Request::getBasePath() }}/task/id/{{ $schedule->id }}/delete";
				}
			}
			
		</script>

		<span class="visible-xs hidden-md">&nbsp;</span>
	</div>
@endif

{!! Form::model($entries, array('action' => array('ScheduleController@updateSchedule', $schedule->id))) !!}
{!! Form::submit('Änderungen speichern', array('class'=>'btn btn-success')) !!}
<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">
		@if($schedule->evnt_id != NULL)
			{{{ $schedule->getClubEvent->evnt_title }}}
		@else
			{{{ $schedule->schdl_title }}}
		@endif
		</h4>
	</div>

	<div class="panel-body">
		@if($schedule->evnt_id != NULL)
			<i>DV-Zeit:</i> {{ date("H:i", strtotime($schedule->schdl_time_preparation_start)) }}
			<br />
			<i>Ort:</i> {{{ $schedule->getClubEvent->getPlace->plc_title }}}
		@else
			<i>Fällig am:</i> {{ strftime("%a, %d. %b", strtotime($schedule->schdl_due_date)) }}
		@endif
	</div>

	@if( $schedule->schdl_password != '')	
		<br />
		<div class="well col-md-5">Eintragen nur mit gültigem Passwort:
		{!! Form::password('password') !!}
		</div>
	@endif


	<table class="table table-condensed table-hover">
		<thead>
		<tr>
			<th>
				&nbsp;
			</th>
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
			<th>
				&nbsp;
			</th>
		</tr>
		</thead>

		<tbody>
			@include('partials.jobsByScheduleId', $entries)
		</tbody>
	</table>

	{!! Form::close() !!}
</div>
@stop