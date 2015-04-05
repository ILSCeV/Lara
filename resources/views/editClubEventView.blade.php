<!-- Needs variables: event, schedule, templates, places, jobtypes, entries -->

@extends('layouts.master')

@section('title')
	Veranstaltung ändern
@stop

@section('content')

@if(Session::has('userId') 
	AND Session::has('userGroup')
	AND (Session::get('userGroup') == 'marketing'
		OR Session::get('userGroup') == 'clubleitung'))

	{!! Form::open(['method' => 'POST', 'route' => ['editClubEvent', $event->id]]) !!}

	<div class="row">
		<div class="container col-md-6">
			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">Veranstaltung ändern:</h4>
				</div>

				<br>

				<div class="form-group">
					<label for="templateName" class="col-md-3 control-label">Vorlagenname: &nbsp;</label>
					<div class="col-md-9">
					@if ($event->getSchedule->schdl_is_template == 1)
						{!! Form::text('templateName', $event->getSchedule->schdl_title, array('id'=>'templateName', 'class'=>'col-md-12') ) !!}
					@else
						{!! Form::text('templateName', '', array('id'=>'templateName', 'class'=>'col-md-12') ) !!}
					@endif
				    </div>
			   	</div>

			   	<br>

			   	<div class="form-group">
					<label for="saveAsTemplate" class="col-md-10 control-label">Veranstaltungsdienstplan als neue Vorlage speichern?</label>
			     	<div class="col-md-2">
						{!! Form::checkbox('saveAsTemplate', '1', false) !!}
					</div>
			   	</div>

			   	<br>
				
				<div class="panel-body">
					<div class="form-group">
				      	<label for="title" class="col-md-2 control-label">Titel:</label>
				      	<div class="col-md-10">
				      		{!! Form::text('title', $event->evnt_title, array('class'=>'form-control', 
				      										  'placeholder'=>'z.B. Weinabend',
				      										  'style'=>'cursor: auto',
				      										  'required') ) !!}
				     	</div>
				    </div>
					
					<br>
				    
				    <div class="form-group">	
						<label for="subtitle" class="col-md-2 control-label">Untertitel:</label>
						<div class="col-md-10">
							{!! Form::text('subtitle', $event->evnt_subtitle, array('class'=>'form-control', 
				      										  'placeholder'=>'z.B. Das Leben ist zu kurz, um schlechten Wein zu trinken', 
				      										  'style'=>'cursor: auto') ) !!}
						</div>
				    </div>
					
					<br><br>

				    <div class="form-group">	
				     	<label for="isPrivate" class="col-md-4 control-label">Interne Veranstaltung?</label>
				     	<div class="col-md-8">
							{!! Form::checkbox('isPrivate', '1', $event->evnt_is_private) !!}&nbsp;
						</div>
				    </div>		    
					
					<br>

					<div class="form-group">
						<label for="place" class="col-md-2 control-label">Ort: &nbsp;</label>
						<div class="col-md-10">
						   	{!! Form::text('place', $places[$event->plc_id], array('id'=>'place') ) !!}
						 	<a class="btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
						        <span class="caret"></span>
						    </a>
						    <ul class="dropdown-menu">
						    @foreach($places as $place)
						        <li> 
						        	<a href="javascript:void(0);" 
						        	   onClick="document.getElementById('place').value='{{$place}}'">{{$place}}</a>
						        </li>
							@endforeach
						    </ul>  	
					    </div>
				   	</div>
					
					<br>

				    <div class="form-group">	
						<label for="beginDate" class="col-md-2 control-label">Beginn:</label>
						<div class="col-md-10">
							{!! Form::input('date', 'beginDate', $event->evnt_date_start) !!} um {!! Form::input('time', 'beginTime', $event->evnt_time_start) !!}
						</div>
				    </div>

				    <div class="form-group">
						<label for="endDate" class="col-md-2 control-label">Ende:</label>
						<div class="col-md-10">
							{!! Form::input('date', 'endDate', $event->evnt_date_end) !!} um {!! Form::input('time', 'endTime', $event->evnt_time_end) !!}
						</div>
				    </div>

				    <br><br><br><br>

				    <div class="form-group col-md-12">
				    	<label for="password" class="col-md-5">Passwort zum Eintragen:</label>
				    	<div class="col-md-7">
				    		{!! Form::password('password', '' ) !!}
				    	</div>
				    </div>

				    <div class="form-group col-md-12">
				    	<label fro="passwordDouble" class="col-md-5">Passwort wiederholen:</label>
				    	<div class="col-md-7">
				    		{!! Form::password('passwordDouble', '') !!}
				    	</div>
				    </div>
				    	<small>Um das Passwort zu löschen trage in beiden Feldern "delete" ein (ohne Anführungszeichen). </small>
				</div>
			</div>
		</div>
				
		<div class="container col-md-6">

			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">Weitere Infos:</h4>(öffentlich)
				</div>
				<div class="panel-body">				
				    <div class="form-group">	
						<div class="col-md-12">
							{!! Form::textarea('publicInfo', $event->evnt_public_info, array('class'=>'form-control', 
																	  'rows'=>'8',
																	  'placeholder'=>'z.B. Karten nur im Vorverkauf') ) !!}
						</div>
					</div>	
				</div>
			</div>

			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">Details:</h4>(nur intern sichtbar)
				</div>
				<div class="panel-body">
				    <div class="form-group">
						<div class="col-md-12">
							{!! Form::textarea('privateDetails', $event->evnt_private_details, array('class'=>'form-control', 
																		  'rows'=>'5', 
																		  'placeholder'=>'z.B. DJ-Tisch wird gebraucht') ) !!}
						</div>
				    </div>
				</div>
			</div>
		</div>
	</div>				
	
	@include('partials.editSchedule', array('schedule', 'templates', 'jobtypes', 'entries'))

	{!! Form::submit('Änderungen speichern', array('class'=>'btn btn-success')) !!}
	&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="javascript:history.back()" class="btn btn-default">Ohne Änderung zurück</a>
	
	{!! Form::close() !!}
	
@else
	@include('partials.accessDenied')
@endif
@stop



