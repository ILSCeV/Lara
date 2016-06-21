@extends('layouts.master')

@section('title')
    Veranstaltung/Aufgabe ändern
@stop

@section('content')

    @if(Session::has('userId')
    AND (Session::get('userGroup') == 'marketing'
     OR Session::get('userGroup') == 'clubleitung'
     OR Session::get('userGroup') == 'admin'
     OR Session::get('userId') == $created_by))

        {!! Form::open(['method' => 'PUT', 'route' => ['event.update', $event->id]]) !!}

        <div class="row">
            <div class="containerPadding15Mobile">
                <div class="panel col-md-6 col-sm-12 col-xs-12">
                    <div class="panel-heading">
                        <h4 class="panel-title">Veranstaltung/Aufgabe ändern:</h4>
                    </div>
                    <br>
                    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
                        {!! Form::checkbox('saveAsTemplate', '1', $event->getSchedule->schdl_is_template, array('class'=>'col-md-1 col-sm-1 col-xs-1', 'hidden')) !!}

			<br>

			<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">			
					{!! Form::checkbox('saveAsTemplate', '1', $event->getSchedule->schdl_is_template, array('class'=>'col-md-1 col-sm-1 col-xs-1', 'hidden')) !!}

					@if ($event->getSchedule->schdl_is_template)
						{!! Form::text('templateName', $event->getSchedule->schdl_title, array('id'=>'templateName', 'hidden') ) !!}
						<label for="saveAsTemplate" class="col-md-12 col-sm-12 col-xs-12">
							{!!'(Gespeichert als Vorlage <b>"' . $event->getSchedule->schdl_title . '"</b>)' !!}
						</label>
					@else
						<label for="saveAsTemplate" class="col-md-12 col-sm-12 col-xs-12">
							{!! "(Dieser Event ist nicht als Vorlage gespeichert.)" !!}
						</label>
					@endif

					
			   	</div>

			<br>
			
			<div class="panel-body no-padding">
				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
			      	<label for="title" class="col-md-2 col-sm-2 col-xs-3">Titel:</label>
		      		{!! Form::text('title', 
		      						$event->evnt_title, 
		      						array('class'=>'col-md-9 col-sm-9 col-xs-8', 
  										  'placeholder'=>'z.B. Weinabend',
  										  'style'=>'cursor: auto',
  										  'required') ) !!}
			    </div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
					<label for="subtitle" class="col-md-2 col-sm-2 col-xs-3">Subtitel:</label>
					{!! Form::text('subtitle', 
									$event->evnt_subtitle, 
									array('class'=>'col-md-9 col-sm-9 col-xs-8', 
  										  'placeholder'=>'z.B. Das Leben ist zu kurz, um schlechten Wein zu trinken', 
  										  'style'=>'cursor: auto') ) !!}
			    </div>
			    
			    @if(Session::get('userGroup') == 'marketing' OR Session::get('userGroup') == 'clubleitung'  OR Session::get('userGroup') == 'admin')
				    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
				     	<label for="evnt_type" class="col-md-2 col-sm-2 col-xs-2">Typ:</label>
				     	<div class="col-md-10 col-sm-10 col-xs-10">
				            {!! Form::radio('evnt_type', "0", $event->evnt_type == 0 ? array("checked") : "") !!}
				            	normales Programm
				            <br>
				            {!! Form::radio('evnt_type', "2", $event->evnt_type == 2 ? array("checked") : "") !!}
				            	Spezial
				            <br>
				            {!! Form::radio('evnt_type', "3", $event->evnt_type == 3 ? array("checked") : "") !!}
				            	Live Band / Live DJ / Lesung 
				            <br>
				            {!! Form::radio('evnt_type', "5", $event->evnt_type == 5 ? array("checked") : "") !!}
				            	Nutzung
				            <br>
				            {!! Form::radio('evnt_type', "4", $event->evnt_type == 4 ? array("checked") : "") !!}
				            	interne Veranstaltung
				            <br>
				            {!! Form::radio('evnt_type', "6", $event->evnt_type == 6 ? array("checked") : "") !!}
				            	Fluten
				            <br>
				            {!! Form::radio('evnt_type', "7", $event->evnt_type == 7 ? array("checked") : "") !!}
				            	Flyer / Plakatieren
				            <br>
				            {!! Form::radio('evnt_type', "8", $event->evnt_type == 8 ? array("checked") : "") !!}
				            	Vorverkauf
				            <br>
				            {!! Form::radio('evnt_type', "9", $event->evnt_type == 9 ? array("checked") : "") !!}
				            	Sonstiges
				            <br>
				            {!! Form::radio('evnt_type', "1", $event->evnt_type == 1 ? array("checked") : "") !!}
				            	Information
				            <br>

				            <br>
				            <div>
								{!! Form::checkbox('isPrivate', '1', ($event->evnt_is_private + 1)%2) !!}
									Für Externe sichtbar machen?
							</div>
				            <br>
							
						</div>
				    </div>
				@else
					<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">	
				     	<label for="evnt_type" class="control-label col-md-2 col-sm-2 col-xs-2">Typ:</label>
				     	<div class="col-md-10 col-sm-10 col-xs-10">
				            @if ($event->evnt_type == 0)
				            	{!! Form::radio('evnt_type', "0", $event->evnt_type == 0 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif       
				            	normales Programm
				            <br>
				            @if ($event->evnt_type == 2)
				            	{!! Form::radio('evnt_type', "2", $event->evnt_type == 2 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif
				            	Spezial
				            <br>
				            @if ($event->evnt_type == 3)
				            	{!! Form::radio('evnt_type', "3", $event->evnt_type == 3 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif
				            	Live Band / Live DJ / Lesung 
				            <br>
				            @if ($event->evnt_type == 5)
				            	{!! Form::radio('evnt_type', "5", $event->evnt_type == 5 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif
				            	Nutzung
				            <br>
				            {!! Form::radio('evnt_type', "4", $event->evnt_type == 4 ? array("checked") : "") !!}
				            	interne Veranstaltung
				            <br>
				            @if ($event->evnt_type == 6)
				            	{!! Form::radio('evnt_type', "6", $event->evnt_type == 6 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif
				            	Fluten
				            <br>
				            @if ($event->evnt_type == 7)
				            	{!! Form::radio('evnt_type', "7", $event->evnt_type == 7 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif
				            	Flyer / Plakatieren
				            <br>
				            @if ($event->evnt_type == 8)
				            	{!! Form::radio('evnt_type', "8", $event->evnt_type == 8 ? array("checked") : "") !!}
				            @else 
				            	&nbsp;<i class="fa fa-times-circle"></i>&nbsp;&nbsp;
				            @endif
				            	Vorverkauf
				            <br>
				            {!! Form::radio('evnt_type', "9", $event->evnt_type == 9 ? array("checked") : "") !!}
				            	Sonstiges
				            <br>
				            {!! Form::radio('evnt_type', "1", $event->evnt_type == 1 ? array("checked") : "") !!}
				            	Information
				            <br>

				            <br>
				            <div>
				            	@if ($event->evnt_is_private == 0)
				            		{!! Form::checkbox('isPrivate', '1', ($event->evnt_is_private + 1)%2) !!}
									Für Externe sichtbar machen?
				            	@else
					            	{!! Form::checkbox('isPrivate', '1', ($event->evnt_is_private + 1)%2, array('hidden')) !!}
									<span style="color: red;">
										<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
										Diese Öffnung wird nur für eingeloggte Mitglieder sichtbar sein!<br>
										Um sie für Externe sichtbar zu machen oder den Typ zu ändern, <br>
										frage die Clubleitung oder die Marketingverantwortlichen.
									</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
                            <label for="place" class="control-label col-md-2 col-sm-2 col-xs-12">Sektion: &nbsp;</label>
					<span class="col-md-10 col-sm-10 col-xs-12">
						{!! Form::text('place', $places[$event->plc_id], array('id'=>'place') ) !!}
                        <a class="btn-small btn-primary dropdown-toggle" data-toggle="dropdown"
                           href="javascript:void(0);">
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
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding" id="filter-checkboxes">
                            <label for="filter" class="control-label col-md-2 col-sm-2 col-xs-12">Zeige für:
                                &nbsp;</label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <div id="filter">
                                    {!! Form::checkbox('filterShowToClub2', '1',
                                            in_array( "bc-Club", json_decode($event->evnt_show_to_club) ) ? true : false ) !!}
                                    bc-Club
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    {!! Form::checkbox('filterShowToClub3', '1',
                                            in_array("bc-Café", json_decode($event->evnt_show_to_club)) ? true : false ) !!}
                                    bc-Café
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
                            <label for="preparationTime"
                                   class="control-label col-md-2 col-sm-2 col-xs-4">DV-Zeit:</label>
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                {!! Form::input('time', 'preparationTime', $event->getSchedule->schdl_time_preparation_start) !!}
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
                            <label for="beginDate" class="control-label col-md-2 col-sm-2 col-xs-12">Beginn:</label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                {!! Form::input('date', 'beginDate', $event->evnt_date_start) !!}
                                <span class="visible-xs"><br></span>um {!! Form::input('time', 'beginTime', $event->evnt_time_start) !!}
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
                            <label for="endDate" class="control-label col-md-2 col-sm-2 col-xs-12">Ende:</label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                {!! Form::input('date', 'endDate', $event->evnt_date_end) !!}
                                <span class="visible-xs"><br></span>um {!! Form::input('time', 'endTime', $event->evnt_time_end) !!}
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            &nbsp;
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
                            <label for="password" class="control-label col-md-5 col-sm-5 col-xs-12">Passwort zum
                                Eintragen:</label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                {!! Form::password('password', '' ) !!}
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
                            <label fro="passwordDouble" class="control-label col-md-5 col-sm-5 col-xs-12">Passwort
                                wiederholen:</label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                {!! Form::password('passwordDouble', '') !!}
                            </div>
                        </div>
                        <div style="color: #ff9800;">
                            <small>Um das Passwort zu löschen trage in beide Felder "delete" ein (ohne
                                Anführungszeichen).
                            </small>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            &nbsp;
                        </div>
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
                                {!! Form::textarea('publicInfo', $event->evnt_public_info, array('class'=>'form-control',
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
                                {!! Form::textarea('privateDetails', $event->evnt_private_details, array('class'=>'form-control',
                                                                              'rows'=>'5',
                                                                              'placeholder'=>'z.B. DJ-Tisch wird gebraucht') ) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <div class="containerPadding15Mobile">
            @include('partials.editSchedule')

        <br>

        {!! Form::submit('Änderungen speichern', array('class'=>'btn btn-success', 'id'=>'button-edit-submit')) !!}
        &nbsp;&nbsp;&nbsp;&nbsp;
        <br class="visible-xs"><br class="visible-xs">
        <a href="javascript:history.back()" class="btn btn-default">Ohne Änderung zurück</a>

			    <div class="col-md-12 col-sm-12 col-xs-12">
			    	&nbsp;
			    </div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
			    	<label for="password" class="control-label col-md-5 col-sm-5 col-xs-12">Passwort zum Eintragen:</label>
			    	<div class="col-md-7 col-sm-7 col-xs-12">
			    		{!! Form::password('password', '' ) !!}
			    	</div>
			    </div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
			    	<label fro="passwordDouble" class="control-label col-md-5 col-sm-5 col-xs-12">Passwort wiederholen:</label>
			    	<div class="col-md-7 col-sm-7 col-xs-12">
			    		{!! Form::password('passwordDouble', '') !!}
			    	</div>
			    </div>

			    <div style="color: #ff9800;">
			    	<small>Um das Passwort zu löschen trage in beide Felder "delete" ein (ohne Anführungszeichen). </small>
			    </div>

			    <div class="col-md-12 col-sm-12 col-xs-12">
			    	&nbsp;
			    </div>

		    </div>	
		</div>

		<div class="container col-xs-12 col-sm-12 col-md-6 no-padding-xs">
			<br class="visible-xs visible-sm">
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
			<br>
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

	<br>
	@include('partials.editSchedule')
	<br>
	
	{!! Form::submit('Änderungen speichern', array('class'=>'btn btn-success', 'id'=>'button-edit-submit')) !!}
	&nbsp;&nbsp;&nbsp;&nbsp;
	<br class="visible-xs"><br class="visible-xs">
	<a href="javascript:history.back()" class="btn btn-default">Ohne Änderung zurück</a>
	
	{!! Form::close() !!}

@else

	<div class="panel panel-warning">
		<div class="panel panel-heading">
			<h4 class="white-text">Ne, das geht so nicht...</h4>
		</div>
		<div class="panel panel-body">
			@if ($creator_name == "")
				<h6>Nur die <b>Clubleitung</b> oder die <b>Marketingverantwortlichen</b> dürfen diese Veranstaltung/Aufgabe ändern.</h6>
			@else
				<h6>Nur <b>{!! $creator_name !!}</b>, die <b>Clubleitung</b> oder die <b>Marketingverantwortlichen</b> dürfen diese Veranstaltung/Aufgabe ändern.</h6>
			@endif
		</div>
	</div>
@endif

@stop



