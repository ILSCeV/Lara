<!-- Needs variables: clubEvent, entries, persons, clubs -->

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
		<div class="container col-xs-12 col-md-6">
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
					<i>DV-Zeit:</i> {{ date("H:i", strtotime($clubEvent->getSchedule->schdl_time_preparation_start)) }}
					<br>
					<i>Ort:</i> {{{ $clubEvent->getPlace->plc_title }}}
				</div>
			</div>
		</div>

		<div class="container col-xs-12 col-md-6">
			@if( $clubEvent->evnt_public_info != '')
			<div class="panel">
				<div class="panel-body more-info">				
					<h5 class="panel-title">Zusatzinfos:</h5> 
					{!! nl2br(e($clubEvent->evnt_public_info)) !!}
				</div>
				<button type="button" class="moreless-more-info btn btn-primary btn-margin" data-dismiss="alert">mehr anzeigen</button>
				<button type="button" class="moreless-less-info btn btn-primary btn-margin" data-dismiss="alert">weniger anzeigen</button>
			</div>
			@endif

			@if(Session::has('userId'))
				@if($clubEvent->evnt_private_details != '') 
				<div class="panel hidden-print">
					<div class="panel-body more-details">
						<h5 class="panel-title">Weitere Details:</h5> 
						{!! nl2br(e($clubEvent->evnt_private_details)) !!}
					</div>
					<button type="button" class="moreless-more-details btn btn-primary btn-margin" data-dismiss="alert">mehr anzeigen</button>
					<button type="button" class="moreless-less-details btn btn-primary btn-margin" data-dismiss="alert">weniger anzeigen</button>
				</div>
				@endif
			@endif
		</div>
	</div>
	<br>
	{!! Form::model($entries, array('action' => array('ScheduleController@updateSchedule', $clubEvent->getSchedule->id))) !!}
	{!! Form::submit('Änderungen speichern', array('class'=>'btn btn-success hidden-print')) !!}
	&nbsp;&nbsp;<button class="btn btn-xs pull-right hidden-print"  type="button" id="show-hide-time">Zeiten ausblenden</button>
	<div class="panel">
		<div class="card-body">
			
			@if( $clubEvent->getSchedule->schdl_password != '')
				<div class="well">
					<strong>Eintragen nur mit gültigem Passwort:&nbsp;&nbsp; {!! Form::password('password', array('required')) !!}</strong>
				</div>
			@endif

			<table class="table table-hover table-condensed">
				<thead>
					
						<th class="hidden-xs">
							&nbsp;
						</th>
						<th class="col-xs-3 col-md-1">
							Dienst
						</th>
						<th class="col-xs-2 col-md-2">
							Name
						</th>
						<th class="col-xs-2 col-md-2">
							Verein
						</th>
						<th class="col-xs-6 col-md-6">
							Kommentar
						</th>
						<th class="hidden-xs">
							&nbsp;
						</th>
					
				</thead>
				<tbody>
				
					@include('partials.jobsByScheduleId', $entries)
				
				</tbody>
			</table>

			{!! Form::close() !!}
		</div>
	</div>

			<!-- CRUD -->
			@if(Session::has('userGroup')
					AND (Session::get('userGroup') == 'marketing'
					OR Session::get('userGroup') == 'clubleitung'))
					<br>
					<div class="pull-right hidden-print">						
						<a href="{{ Request::getBasePath() }}/calendar/id/{{ $clubEvent->id }}/edit" 
						   class="btn btn-primary">Veranstaltung ändern</a>

						<span class="visible-xs">&nbsp;</span>
						<span class="hidden-xs">&nbsp;&nbsp;&nbsp;&nbsp;</span>

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

	@endif

	@if(Session::has('userId'))

		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="show-hide-history" class="text-muted" href="#">Liste der Änderungen &nbsp;&nbsp;<i class="fa fa-caret-right" id="arrow-icon"></i></a>
		<br>
		<br>
		<div class="panel hide" id="change-history">
			<div class="card-body">
				<table class="table table-hover table-condensed">
					<thead>
						<th class="hidden-xs">
							&nbsp;
						</th>
						<th class="col-xs-2 col-md-2">
							Dienst
						</th>
						<th class="col-xs-2 col-md-2">
							Was wurde geändert?
						</th>
						<th class="col-xs-2 col-md-2">
							Alter Eintrag
						</th>
						<th class="col-xs-2 col-md-2">
							Neuer Eintrag
						</th>
						<th class="col-xs-2 col-md-2">
							Wer ist schuld?
						</th>
						<th class="col-xs-2 col-md-2">
							Wann war das?
						</th>
					</thead>
					<tbody>

					@for ($i = 0; $i < count($revisions); $i++)
					    <tr>
					    	<td>
					    		&nbsp;
					    	</td>
					    	<td>
					    		{{ $revisions[$i]["job type"] }}
					    	</td>
					    	<td>
					    		{{ $revisions[$i]["action"] }}
					    	</td>
					    	<td>
					    		{{ $revisions[$i]["old value"] }}
					    	</td>
					    	<td>
					    		{{ $revisions[$i]["new value"] }}
					    	</td>
					    	<td>
					    		{{ $revisions[$i]["user name"] }}
					    	</td>
					    	<td>
					    		{{ $revisions[$i]["timestamp"] }}
					    	</td>
					    </tr>
					@endfor
					</tbody>
				</table>
			</div>	
		</div>
	@endif
@stop



