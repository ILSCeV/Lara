@extends('layouts.master')

@section('title')
	Neue Veranstaltung erstellen
@stop

@section('content')

@if(Session::has('userId'))
	
	{!! Form::open(['method' => 'POST', 'route' => ['event.store']]) !!}

	<div class="row">
		<div class="panel col-md-6 col-sm-12 col-xs-12">

			<div class="panel-heading">
				<h4 class="panel-title">Neue Veranstaltung erstellen:</h4>
			</div>

			<br>
			
			<div class="panel-body no-padding">

				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="templateName" class="control-label col-md-2 col-sm-2 col-xs-3">Vorlage: &nbsp;</label>
					
					<div class="col-md-6 col-sm-6 col-xs-9">
						@if (isset($activeTemplate))
							{!! Form::text('templateName', $activeTemplate, array('id'=>'templateName', 'class'=>'col-xs-10 col-md-9') ) !!}
						@else
							{!! Form::text('templateName', '', array('id'=>'templateName', 'class'=>'col-xs-10 col-md-9') ) !!}
						@endif
						<span class="hidden-xs">&nbsp;&nbsp;</span>
						<a class="btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
					        <span class="caret"></span>
					    </a>
					    <ul class="dropdown-menu">
						    @foreach($templates as $template)
						        <li> 
						        	<a href="javascript:void(0);" 
						        	   onClick="document.getElementById('templateName').value='{{$template->schdl_title}}',
						        	   			window.location.href='{{ Request::getBasePath() }}/event/{{ substr($date, 6, 4) }}/{{ substr($date, 3, 2) }}/{{ substr($date, 0, 2) }}/{{ $template->id }}/create';">
						        	   {{ $template->schdl_title }}</a>
						        </li>
							@endforeach
					    </ul>
				    </div>
			   	</div>

			   	<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding col-md-offset-2 col-sm-offset-2 col-xs-offset-1">			
					{!! Form::checkbox('saveAsTemplate', '1', false, array('class'=>'col-md-1 col-sm-1 col-xs-1')) !!}
					Als neue Vorlage speichern?
			   	</div>

				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
			      	<label for="title" class="col-md-2 col-sm-2 col-xs-3">Titel:</label>
		      		{!! Form::text( 'title', '', array('placeholder'=>'z.B. Weinabend',
		      										   'style'=>'cursor: auto',
		      										   'class'=>'col-md-9 col-sm-9 col-xs-8',
		      										   'required') ) !!}
			    </div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
					<label for="subtitle" class="col-md-2 col-sm-2 col-xs-3">Subtitel:</label>
					{!! Form::text('subtitle', '', array('placeholder'=>'z.B. Das Leben ist zu kurz, um schlechten Wein zu trinken', 
														 'class'=>'col-md-9 col-sm-9 col-xs-8',
		      										     'style'=>'cursor: auto') ) !!}
			    </div>
			    
			    @if(Session::get('userGroup') == 'marketing' OR Session::get('userGroup') == 'clubleitung')
				    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
				     	<label for="evnt_type" class="col-md-2 col-sm-2 col-xs-2">Typ:</label>
				     	<div class="col-md-10 col-sm-10 col-xs-10">
				            {!! Form::radio('evnt_type', "0", array("checked")) !!}
				            	normales Programm
				            <br>
				            {!! Form::radio('evnt_type', "2") !!}
				            	Spezial
				            <br>
				            {!! Form::radio('evnt_type', "3") !!}
				            	Live Band / Live DJ / Lesung 
				            <br>
				            {!! Form::radio('evnt_type', "5") !!}
				            	Nutzung
				            <br>
				            {!! Form::radio('evnt_type', "4") !!}
				            	interne Veranstaltung
				            <br>
				            {!! Form::radio('evnt_type', "6") !!}
				            	Fluten
				            <br>
				            {!! Form::radio('evnt_type', "7") !!}
				            	Flyer / Plakatieren
				            <br>
				            {!! Form::radio('evnt_type', "8") !!}
				            	Vorverkauf
				            <br>
				            {!! Form::radio('evnt_type', "9") !!}
				            	weitere interne Aufgaben
				            <br>
				            {!! Form::radio('evnt_type', "1") !!}
				            	Information
				            <br>

				            <br>
				            <div>
								{!! Form::checkbox('isPrivate', '1', true) !!}
									Für Externe sichtbar machen?
							</div>
				            <br>
							
						</div>
				    </div>
				@else
					<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
				     	<label for="evnt_type" class="control-label col-md-2 col-sm-2 col-xs-2">Typ:</label>
				     	<div class="col-md-10 col-sm-10 col-xs-10">
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
				            	normales Programm
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
				            	Spezial
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
				            	Live Band / Live DJ / Lesung 
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
				            	Nutzung
				            <br>
				            {!! Form::radio('evnt_type', "4", array("checked")) !!}
				            	interne Veranstaltung
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
				            	Fluten
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
				            	Flyer / Plakatieren
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
				            	Vorverkauf
				            <br>
				            {!! Form::radio('evnt_type', "9") !!}
				            	weitere interne Aufgaben
				            <br>
				            {!! Form::radio('evnt_type', "1") !!}
				            	Information
				            <br>

				            <br>
				            <div>
			            	{!! Form::checkbox('isPrivate', '1', false, array('hidden')) !!}
							<span style="color: red;">
								<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
								Diese Öffnung wird nur für eingeloggte Mitglieder sichtbar sein!<br>
								Um sie für Externe sichtbar zu machen oder den Typ zu ändern, <br>
								frage die Clubleitung oder die Marketingverantwortliche.
							</span>
			            
				            </div>
							
						</div>
				    </div>					
				@endif

				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="place" class="control-label col-md-2 col-sm-2 col-xs-12">Sektion: &nbsp;</label>
					<span class="col-md-10 col-sm-10 col-xs-12">
						{{-- Set default values to the club the user is a member in.
						 	 This saves time retyping event start/end times, etc. --}}
						@if(Session::get('userClub') == 'bc-Club')
							{!! Form::text('place', 'bc-Club', array('id'=>'place') ) !!}
						@elseif(Session::get('userClub') == 'bc-Café')
							{!! Form::text('place', 'bc-Café', array('id'=>'place') ) !!}
						@endif 	   	
					 	<a class="btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
					        <span class="caret"></span>
					    </a>
					    <ul class="dropdown-menu">
						    @foreach($places as $place)
						        <li> 
						        	<a href="javascript:void(0);" 
						        	   onClick="document.getElementById('place').value='{{$place}}'">{{ $place }}</a>
						        </li>
							@endforeach
					    </ul>  	
				    </span>
			   	</div>

			   	<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="filter" class="control-label col-md-2 col-sm-2 col-xs-12">Zeige für: &nbsp;</label>
					<div class="col-md-10 col-sm-10 col-xs-12">
						{{-- Set default values to the club the user is a member in.
						 	 This saves time changing defaults for other sections --}}
						@if(Session::get('userClub') == 'bc-Club')
							<div id="filter">
								{!! Form::checkbox('filterShowToClub2', '1', true) !!}
									bc-Club
								&nbsp;&nbsp;&nbsp;&nbsp;
								{!! Form::checkbox('filterShowToClub3', '1', false) !!}
									bc-Café
							</div>
						@elseif(Session::get('userClub') == 'bc-Café')
							<div id="filter">
								{!! Form::checkbox('filterShowToClub2', '1', false) !!}
									bc-Club
								&nbsp;&nbsp;&nbsp;&nbsp;
								{!! Form::checkbox('filterShowToClub3', '1', true) !!}
									bc-Café
							</div>
						@endif 	   	
				    </div>
			   	</div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
					<label for="preparationTime" class="control-label col-md-2 col-sm-2 col-xs-4">DV-Zeit:</label>
					<div class="col-md-3 col-sm-3 col-xs-3">
						{{-- Set default values to the club the user is a member in.
						 	 This saves time retyping event start/end times, etc. --}}
						@if(Session::get('userClub') == 'bc-Club')
							{!! Form::input('time', 'preparationTime', '20:00' ) !!}
						@elseif(Session::get('userClub') == 'bc-Café')
							<span class="hidden-xs">&nbsp;&nbsp;</span>
							{!! Form::input('time', 'preparationTime', '10:45' ) !!}
						@endif
					</div>
			    </div>
			    
			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
					<label for="beginDate" class="control-label col-md-2 col-sm-2 col-xs-12">Beginn:</label>
					<div class="col-md-10 col-sm-10 col-xs-12">
						{{-- Set default values to the club the user is a member in.
						 	 This saves time retyping event start/end times, etc. --}}
						@if(Session::get('userClub') == 'bc-Club')
							{!! Form::input('date', 'beginDate', date("Y-m-d", strtotime($date))) !!} 
								um {!! Form::input('time', 'beginTime', '21:00') !!}
						@elseif(Session::get('userClub') == 'bc-Café')
							{!! Form::input('date', 'beginDate', date("Y-m-d", strtotime($date))) !!} 
								um {!! Form::input('time', 'beginTime', '12:00') !!}
						@endif
					</div>
			    </div>

				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="endDate" class="control-label col-md-2 col-sm-2 col-xs-12">Ende:</label>
					<div class="col-md-10 col-sm-10 col-xs-12">
						{{-- Set default values to the club the user is a member in.
						 	 This saves time retyping event start/end times, etc. --}}
						@if(Session::get('userClub') == 'bc-Club')
							{!! Form::input('date', 'endDate', date("Y-m-d", strtotime("+1 day", strtotime($date)))) !!} 
								um {!! Form::input('time', 'endTime', '01:00') !!}
						@elseif(Session::get('userClub') == 'bc-Café')
							{!! Form::input('date', 'endDate', date("Y-m-d", strtotime($date))) !!} 
								um {!! Form::input('time', 'endTime', '17:00') !!}
						@endif
					</div>
			    </div>

			    <div class="col-md-12 col-sm-12 col-xs-12">
			    	&nbsp;
			    </div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
			    	<label for="password" class="control-label col-md-5 col-sm-5 col-xs-12">Passwort zum Eintragen:</label>
			    	<div class="col-md-7 col-sm-7 col-xs-12">
			    		{!! Form::password('password', '' ) !!}
			    	</div>
			    </div>

			    <div class="form-groupcol-md-12 col-sm-12 col-xs-12 no-padding">
			    	<label fro="passwordDouble" class="control-label col-md-5 col-sm-5 col-xs-12">Passwort wiederholen:</label>
			    	<div class="col-md-7 col-sm-7 col-xs-12">
			    		{!! Form::password('passwordDouble', '') !!}
			    	</div>
			    </div>

			    <div class="col-md-12 col-sm-12 col-xs-12">
			    	&nbsp;
			    </div>

		    </div>	
		</div>

		<div class="container col-xs-12 col-sm-12 col-md-6">
			<br class="visible-xs visible-sm">
			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">Weitere Infos:</h4>(öffentlich)
				</div>
				<div class="panel-body">				
				    <div class="form-group">	
						<div class="col-md-12">
							{!! Form::textarea('publicInfo', '', array('class'=>'form-control', 
																	  'rows'=>'8',
																	  'placeholder'=>'z.B. Karten nur im Vorverkauf') ) !!}
						</div>
					</div>	
				</div>
			</div>
			<br>
			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">Details:</h4>(nur intern sichtbar)
				</div>
				<div class="panel-body">
				    <div class="form-group">
						<div class="col-md-12">
							{!! Form::textarea('privateDetails', '', array('class'=>'form-control', 
																		  'rows'=>'5', 
																		  'placeholder'=>'z.B. DJ-Tisch wird gebraucht') ) !!}
						</div>
				    </div>
				</div>
			</div>
		</div>
	</div>

	<br>
	@include('partials.editSchedule')
	<br>
	
	{!! Form::submit('Veranstaltung mit Dienstplan erstellen', array('class'=>'btn btn-primary')) !!}
	&nbsp;&nbsp;&nbsp;&nbsp;
	<br class="visible-xs">
	<a href="javascript:history.back()" class="btn btn-default">Ohne Änderung zurück</a>

	{!! Form::close() !!}
	
@else
	@include('partials.accessDenied')
@endif
@stop



