@extends('layouts.master')

@section('title')
	{{ $clubEvent->evnt_title }}
@stop

@section('content')

	<div class="row">
		<div class="container col-xs-12 col-md-6">
			<div class="panel">
				@if ($clubEvent->getPlace->plc_title == "bc-Club" AND $clubEvent->evnt_is_private )
					<div class="panel panel-heading calendar-internal-event-bc-club white-text">
				@elseif ($clubEvent->getPlace->plc_title == "bc-Café" AND $clubEvent->evnt_is_private)
					<div class="panel panel-heading calendar-internal-event-bc-cafe white-text">
				@elseif ($clubEvent->getPlace->plc_title == "bc-Club")
					<div class="panel panel-heading calendar-public-event-bc-club white-text">
				@elseif ($clubEvent->getPlace->plc_title == "bc-Café")
					<div class="panel panel-heading calendar-public-event-bc-cafe white-text">
				@else
					<div class="panel panel-heading calendar-task white-text">
				@endif
					<h4 class="panel-title">{{{ $clubEvent->evnt_title }}}</h4>
					<h5 class="panel-title">{{{ $clubEvent->evnt_subtitle }}}</h5>
				</div>
					<table class="table table-hover">
						<tr>
							<td width="20%">
								&nbsp;&nbsp;<i>Typ:</i>	
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
								@endif
							</td>
						</tr>
						<tr>
							<td width="20%">
								&nbsp;&nbsp;<i>Beginn:</i>
							</td>
							<td> 
								{{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start)) }} um 
								{{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
							</td>
						</tr>
						<tr>
							<td width="20%">
								&nbsp;&nbsp;<i>Ende:</i>
							</td>
							<td>
								{{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_end)) }} um 
								{{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
							</td>
						</tr>
						<tr>
							<td width="20%">
								&nbsp;&nbsp;<i>DV-Zeit:</i>
							</td>
							<td>
								{{ date("H:i", strtotime($clubEvent->getSchedule->schdl_time_preparation_start)) }}
							</td>
						</tr>
						<tr>
							<td width="20%">
								&nbsp;&nbsp;<i>Verein:</i>
							</td>
							<td>
								{{{ $clubEvent->getPlace->plc_title }}}
							</td>
						</tr>
					</table>
					
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

	&nbsp;&nbsp;<button class="btn btn-xs pull-right hidden-print"  type="button" id="show-hide-time">Zeiten ausblenden</button>
	
	<div class="panel panel-warning">	
		@if( $clubEvent->getSchedule->schdl_password != '')
			<div class="hidden-print panel panel-heading">
			    {!! Form::password('password', array('required', 
			                                         'class'=>'col-md-4 col-xs-12',
			                                         'placeholder'=>'Passwort hier eingeben')) !!}
			    <br />
			</div> 

		@endif 

		<div class="panel-body">
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
			        <div class="col-xs-3 col-md-2">
			            @include("partials.scheduleEntryTitle")
			        </div>
			        
			        {{-- show public events, but protect members' entries from being changed by guests --}}
			        @if( isset($entry->getPerson->prsn_ldap_id) AND !Session::has('userId'))

						<div class="col-xs-5 col-md-2 input-append btn-group">
						    {{-- ENTRY STATUS --}}
						    <div class="col-xs-2 col-md-2 no-padding" id="clubStatus{{ $entry->id }}">
						        @include("partials.ScheduleEntryStatus")
						    </div>

						    {{-- ENTRY USERNAME--}}
						    <div id="{!! 'userName' . $entry->id !!}">
						        {!! $entry->getPerson->prsn_name !!}
						    </div>

						    {{-- no need to show LDAP ID or TIMESTAMP in this case --}}
						</div>

						{{-- ENTRY CLUB --}}
						<div id="{!! 'club' . $entry->id !!}" class="col-xs-3 col-md-2">
						    {!! "(" . $entry->getPerson->getClub->clb_title . ")" !!}
						</div>

						{{-- LARGE COMMENT SECTION --}}        
						<div id="{!! 'comment' . $entry->id !!}"
						     class="col-md-6 hidden-print word-break hidden-xs">
						    {!! !empty($entry->entry_user_comment) ? $entry->entry_user_comment : "-" !!}
						</div>

						{{-- SMALL COMMENT SECTION --}}
						{{-- Show only the icon first --}}
						<div class="col-xs-1 col-md-1 no-padding visible-xs">      
						    @if( $entry->entry_user_comment == "" )
						        <button type="button" class="showhide btn-small btn-default hidden-print" data-dismiss="alert">
						            <i class="fa fa-comment-o"></i>
						        </button>
						    @else
						        <button type="button" class="showhide btn-small btn-default hidden-print" data-dismiss="alert">
						            <i class="fa fa-comment"></i>
						        </button>
						    @endif
						</div>

						{{-- Hidden comment field to be opened after the click on the icon 
						     see vedst-scripts "Show/hide comments" function --}}         
						<div id="{!! 'comment' . $entry->id !!}"
						     class="col-xs-10 col-md-10 hidden-print hide col-md-offset-1 word-break">
						    {!! !empty($entry->entry_user_comment) ? $entry->entry_user_comment : "-" !!}
						</div>

			        {{-- show everything for members --}}
					@else

			        	{{-- ENTRY STATUS, USERNAME, DROPDOWN USERNAME and LDAP ID --}}
						<div class="col-xs-5 col-md-2 input-append btn-group">      
						    @include("partials.scheduleEntryName")
						</div>                
						        
						{{-- ENTRY CLUB and DROPDOWN CLUB --}}
						<div class="col-xs-3 col-md-2">
						    @include("partials.scheduleEntryClub")                 
						</div>   

						{{-- LARGE COMMENT SECTION --}} 
						<div class="col-md-6 hidden-xs">
						    @if( is_null($entry->getPerson) )   
							    {!! Form::text('comment' . $entry->id, 
							                   Input::old('comment' . $entry->id),  
							                   array('placeholder'=>'Kommentar hier hinzufügen',
							                         'id'=>'comment' . $entry->id, 
							                         'class'=>'col-xs-12 col-md-12')) 
							    !!}
							 @else
							    {!! Form::text('comment' . $entry->id, 
							                   $entry->entry_user_comment, 
							                   array('placeholder'=>'Kommentar hier hinzufügen',
							                         'id'=>'comment' . $entry->id,
							                         'class'=>'col-xs-12 col-md-12')) 
							    !!}
							@endif
						</div>

						{{-- SMALL COMMENT SECTION --}}
						{{-- Show only the icon first --}}
						<div class="col-xs-1 visible-xs no-padding">      
						    @if( $entry->entry_user_comment == "" )
						        <button type="button" class="showhide btn-small btn-default hidden-print" data-dismiss="alert">
						            <i class="fa fa-comment-o"></i>
						        </button>
						    @else
						        <button type="button" class="showhide btn-small btn-default hidden-print" data-dismiss="alert">
						            <i class="fa fa-comment"></i>
						        </button>
						    @endif
						</div>

						{{-- Hidden comment field to be opened after the click on the icon 
							 see vedst-scripts "Show/hide comments" function --}}
						{!! Form::text('comment' . $entry->id, 
						               $entry->entry_user_comment, 
						               array('placeholder'=>'Kommentar hier hinzufügen',
						                     'id'=>'comment' . $entry->id,
						                     'class'=>'col-xs-10 col-xs-offset-1 hidden-print hide' )) 
						!!}
						  
			        @endif
			            
			        {!! Form::submit( 'save', array('id' => 'btn-submit-changes' . $entry->id, 'hidden') ) !!}
			        {!! Form::close() !!}

				</div>
				
				<br class="visible-xs">

			@endforeach
		</div>

	</div>

	<br>

	@if(Session::has('userId'))
		{{-- REVISIONS --}}
		<span class="hidden-xs">&nbsp;&nbsp;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<a id="show-hide-history" class="text-muted" href="#">
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

	{{-- CRUD --}}
	@if(Session::has('userGroup')
			AND (Session::get('userGroup') == 'marketing'
			OR Session::get('userGroup') == 'clubleitung'))
			<div class="pull-right hidden-print">						
				<a href="{{ URL::route('event.edit', $clubEvent->id) }}" 
				   class="btn btn-primary">Veranstaltung ändern</a>

				<span class="visible-xs">&nbsp;</span>
				<span class="hidden-xs">&nbsp;&nbsp;&nbsp;&nbsp;</span>

					<a href="{{ $clubEvent->id }}"
					   class="btn btn-default" 
					   data-method="delete" 
					   data-token="{{csrf_token()}}"
					   rel="nofollow" 
					   data-confirm="Diese Veranstaltung wirklich entfernen? Diese Aktion kann nicht rückgängig gemacht werden!">Veranstaltung löschen</a>
			</div>
	@endif
@stop



