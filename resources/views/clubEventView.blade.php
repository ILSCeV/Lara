@extends('layouts.master')

@section('title')
	{{ $clubEvent->evnt_title }}
@stop

@section('content')

	<div class="row no-margin">
		<div class="panel col-xs-12 col-md-6 no-padding">
			@if	($clubEvent->evnt_type == 1 AND $clubEvent->evnt_is_private)
				<div class="panel panel-heading calendar-internal-info white-text">			
			@elseif     ($clubEvent->evnt_type == 1)
				<div class="panel panel-heading calendar-public-info white-text">

			@elseif (($clubEvent->evnt_type == 6 OR $clubEvent->evnt_type == 9) AND $clubEvent->evnt_is_private)
				<div class="panel panel-heading calendar-internal-task white-text">
			@elseif ($clubEvent->evnt_type == 6 OR $clubEvent->evnt_type == 9)
				<div class="panel panel-heading calendar-public-task white-text">


			@elseif (($clubEvent->evnt_type == 7 OR $clubEvent->evnt_type == 8) AND $clubEvent->evnt_is_private)
				<div class="panel panel-heading calendar-internal-marketing white-text">
			@elseif ($clubEvent->evnt_type == 7 OR $clubEvent->evnt_type == 8)
				<div class="panel panel-heading calendar-public-marketing white-text">


			@elseif ($clubEvent->getPlace->plc_title == "bc-Club" AND $clubEvent->evnt_is_private )
				<div class="panel panel-heading calendar-internal-event-bc-club white-text">
			@elseif ($clubEvent->getPlace->plc_title == "bc-Club")
				<div class="panel panel-heading calendar-public-event-bc-club white-text">

			@elseif ($clubEvent->getPlace->plc_title == "bc-Café" AND $clubEvent->evnt_is_private)
				<div class="panel panel-heading calendar-internal-event-bc-cafe white-text">
			@elseif ($clubEvent->getPlace->plc_title == "bc-Café")
				<div class="panel panel-heading calendar-public-event-bc-cafe white-text">
				
			@else 
				{{-- DEFAULT --}}
				<div class="panel panel-heading white-text">		
			@endif
				<h4 class="panel-title">@include("partials.event-marker")&nbsp;{{{ $clubEvent->evnt_title }}}</h4>
				<h5 class="panel-title">{{{ $clubEvent->evnt_subtitle }}}</h5>
			</div>
				<table class="table table-hover">
					<tr>
						<td width="20%" class="left-padding-16">
							<i>Typ:</i>	
						</td>
						<td>
							@if( $clubEvent->evnt_type == 0)
								normales Programm
							@elseif( $clubEvent->evnt_type == 1)
								Information
							@elseif( $clubEvent->evnt_type == 2)
								Spezial
							@elseif( $clubEvent->evnt_type == 3)
								Live Band / Live DJ / Lesung
							@elseif( $clubEvent->evnt_type == 4)
								interne Veranstaltung
							@elseif( $clubEvent->evnt_type == 5)
								Nutzung
							@elseif( $clubEvent->evnt_type == 6)
								Fluten
							@elseif( $clubEvent->evnt_type == 7)
								Flyer / Plakatieren
							@elseif( $clubEvent->evnt_type == 8)
								Vorverkauf
							@elseif( $clubEvent->evnt_type == 9)
								interne Aufgabe
							@endif
						</td>
					</tr>
					<tr>
						<td width="20%" class="left-padding-16">
							<i>Beginn:</i>
						</td>
						<td> 
							{{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start)) }} um 
							{{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
						</td>
					</tr>
					<tr>
						<td width="20%" class="left-padding-16">
							<i>Ende:</i>
						</td>
						<td>
							{{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_end)) }} um 
							{{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
						</td>
					</tr>
					<tr>
						<td width="20%" class="left-padding-16">
							<i>DV-Zeit:</i>
						</td>
						<td>
							{{ date("H:i", strtotime($clubEvent->getSchedule->schdl_time_preparation_start)) }}
						</td>
					</tr>
					<tr>
						<td width="20%" class="left-padding-16">
							<i>Verein:</i>
						</td>
						<td>
							{{{ $clubEvent->getPlace->plc_title }}} 
							&nbsp;&nbsp;<br class="visible-xs">
							<i>(wird angezeigt für: {{ implode(", ", json_decode($clubEvent->evnt_show_to_club, true)) }})</i>
						</td>
					</tr>
				</table>
			{{-- CRUD --}}
			@if(Session::has('userGroup')
				AND (Session::get('userGroup') == 'marketing'
				OR Session::get('userGroup') == 'clubleitung'
				OR Session::get('userId') == $created_by))
				<div class="panel panel-footer col-md-12 col-xs-12 hidden-print">	
					<span class="pull-right">
						<a href="{{ URL::route('event.edit', $clubEvent->id) }}" 
						   class="btn btn-primary"
						   data-toggle="tooltip" 
	                       data-placement="bottom" 
	                       title="Veranstaltung ändern">
						   <i class="fa fa-pencil"></i>
						</a>
						&nbsp;&nbsp;
						<a href="{{ $clubEvent->id }}"
						   class="btn btn-default"
						   data-toggle="tooltip" 
	                       data-placement="bottom" 
	                       title="Veranstaltung löschen" 
						   data-method="delete" 
						   data-token="{{csrf_token()}}"
						   rel="nofollow" 
						   data-confirm="Diese Veranstaltung wirklich entfernen? Diese Aktion kann nicht rückgängig gemacht werden!">
						   <i class="fa fa-trash"></i>
						</a>
					</span>
				</div>
			@endif
		</div>

		<div class="col-xs-12 col-md-6 no-padding left-padding-16">
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
	&nbsp;&nbsp;<button class="btn btn-xs pull-right hidden-print"  type="button" id="show-hide-time">Zeiten ausblenden</button>
	
	<div class="panel panel-warning">	
		@if( $clubEvent->getSchedule->schdl_password != '')
			<div class="hidden-print panel panel-heading">
			    {!! Form::password('password', array('required', 
			                                         'class'=>'col-md-4 col-xs-12 black-text',
		                                             'id'=>'password' . $clubEvent->getSchedule->id,
			                                         'placeholder'=>'Passwort hier eingeben')) !!}
			    <br />
			</div> 

		@endif 

		<div class="panel-body no-padding">
			@foreach($entries as $entry)	
				<div class="row">
			        {!! Form::open(  array( 'route' => ['entry.update', $entry->id],
			                                'id' => $entry->id, 
			                                'method' => 'put', 
			                                'class' => 'scheduleEntry')  ) !!}

			        {{-- SPAMBOT HONEYPOT - this field will be hidden, so if it's filled, then it's a bot or a user tampering with page source --}}
			        <div id="welcome-to-our-mechanical-overlords">
			            <small>If you can read this this - refresh the page to update CSS styles or switch CSS support on.</small>
			            <input type="text" id="{!! 'website' . $entry->id !!}" name="{!! 'website' . $entry->id !!}" value="" />
			        </div>

			        {{-- ENTRY TITLE --}}
			        <div class="col-md-2 col-xs-3 left-padding-8">
			            @include("partials.scheduleEntryTitle")
			        </div>
			        
			        {{-- show public events, but protect members' entries from being changed by guests --}}
			        @if( isset($entry->getPerson->prsn_ldap_id) AND !Session::has('userId'))

						<div class="col-md-2 col-xs-5 input-append btn-group">
						    {{-- ENTRY STATUS --}}
						    <div class="col-md-2 col-xs-3 no-padding" id="clubStatus{{ $entry->id }}">
						        @include("partials.scheduleEntryStatus")
						    </div>

						    {{-- ENTRY USERNAME--}}
						    <div id="{!! 'userName' . $entry->id !!}">
						        {!! $entry->getPerson->prsn_name !!}
						    </div>

						    {{-- no need to show LDAP ID or TIMESTAMP in this case --}}
						</div>

						{{-- ENTRY CLUB --}}
						<div id="{!! 'club' . $entry->id !!}" class="col-md-2 col-xs-4">
						    {!! "(" . $entry->getPerson->getClub->clb_title . ")" !!}
						</div>

						<br class="visible-xs">

						{{-- COMMENT SECTION --}}	        
						<div class="col-md-6 col-xs-12 hidden-print word-break no-margin">
						    <span class="pull-left">
						    	{!! $entry->entry_user_comment == "" ? '<i class="fa fa-comment-o"></i>' : '<i class="fa fa-comment"></i>' !!}
						    	&nbsp;&nbsp;
						    </span>

						    <span class="col-md-10 col-xs-10 no-padding no-margin">
							    {!! !empty($entry->entry_user_comment) ? $entry->entry_user_comment : "-" !!}
							</span>
						</div>
						

			        {{-- show everything for members --}}
					@else

			        	{{-- ENTRY STATUS, USERNAME, DROPDOWN USERNAME and LDAP ID --}}
						<div class="col-md-2 col-xs-5 input-append btn-group">      
						    @include("partials.scheduleEntryName")
						</div>                
						        
						{{-- ENTRY CLUB and DROPDOWN CLUB --}}
						<div class="col-md-2 col-xs-4 no-padding">
						    @include("partials.scheduleEntryClub")                 
						</div>   

						{{-- COMMENT SECTION --}}	
						<br class="visible-print hidden-md hidden-sm hidden-xs">
						<br class="visible-print hidden-md hidden-sm hidden-xs">   
						<div class="col-md-6 col-xs-12 no-margin">
						    <span class="pull-left">
						    	{!! $entry->entry_user_comment == "" ? '<i class="fa fa-comment-o"></i>' : '<i class="fa fa-comment"></i>' !!}
						    	&nbsp;&nbsp;
						    </span>
						    
						    {!! Form::text('comment' . $entry->id, 
					                   $entry->entry_user_comment, 
					                   array('placeholder'=>'Kommentar hier hinzufügen',
					                         'id'=>'comment' . $entry->id,
			                     			 'name'=>'comment' . $entry->id,
					                         'class'=>'col-md-11 col-xs-10 no-padding no-margin')) 
					    	!!}	
						</div>
						<br class="visible-print hidden-md hidden-sm hidden-xs">    

			        @endif
			            
			        {!! Form::close() !!}

				</div>

				{{-- Show a line after each row except the last one --}}
				@if($entry !== $entries->last() ) 
					<hr class="col-md-12 col-xs-12 top-padding no-margin no-padding">
				@endif

			@endforeach
		</div>

	</div>

	<br>

	@if(Session::has('userId'))
		{{-- REVISIONS --}}
		<span class="hidden-xs">&nbsp;&nbsp;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<a id="show-hide-history" class="text-muted hidden-print" href="#">
			Liste der Änderungen &nbsp;&nbsp;<i class="fa fa-caret-right" id="arrow-icon"></i>
		</a>

		<div class="panel hide" id="change-history">
			<table class="table table-hover table-condensed">
				<thead>
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
		<br>
		<br class="visible-xs">
	@endif

@stop



