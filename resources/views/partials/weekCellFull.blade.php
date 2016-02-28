<div class="panel panel-default">

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

			<h4 class="panel-title">
				<a href="{{ URL::route('event.show', $clubEvent->id) }}"> 
					<span class="name">{{{ $clubEvent->evnt_title }}}</span>
				</a>
			</h4>
			
			{{ utf8_encode(strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start))) }} 
			&nbsp;
			DV: {{ date("H:i", strtotime($clubEvent->getSchedule->schdl_time_preparation_start)) }}
			&nbsp;
			<i class="fa fa-clock-o"></i> {{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
			-
			{{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
			&nbsp;
			<i class="fa fa-map-marker">&nbsp;</i>{{{ $clubEvent->getPlace->plc_title }}}

		</div>

		<div class="panel-body">

			@if (!is_null($clubEvent->getSchedule))	

				{{-- Show password input if schedule needs one --}}
				@if( $clubEvent->getSchedule->schdl_password != '')
				    <div class="well no-padding hidden-print">
				        {!! Form::password('password', array('required', 
				                                             'class'=>'col-md-12 col-xs-12',
				                                             'placeholder'=>'Passwort hier eingeben')) !!}
				        <br />
				    </div> 
				@endif 

				{{-- Show schedule entries --}}
				@foreach($clubEvent->getSchedule->getEntries as $entry)
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
				        <div class="col-xs-3 col-md-3">
				            @include("partials.scheduleEntryTitle")
				        </div>
				        
				        {{-- ENTRY STATUS, USERNAME, DROPDOWN USERNAME and LDAP ID --}}
						<div class="col-xs-5 col-md-5 input-append btn-group">      
						    @include("partials.scheduleEntryName")
						</div>                
						        
						{{-- ENTRY CLUB and DROPDOWN CLUB --}}
						<div class="col-xs-3 col-md-3">
						    @include("partials.scheduleEntryClub")                 
						</div>   

						{{-- SMALL COMMENT ICON --}}
						<div class="col-xs-1 col-md-1 no-padding">      
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
						                     'class'=>'col-xs-10 col-md-10 hidden-print hide col-md-offset-1 col-xs-offset-1 word-break' )) 
						!!}
				           
				        {!! Form::submit( 'save', array('id' => 'btn-submit-changes' . $entry->id, 'hidden') ) !!}
				        {!! Form::close() !!}

				    </div>

				    <br class="visible-xs">

				@endforeach

			@endif
			
			{{-- Show a "hide" button for management, that allows removal of an event from current view - needed for printing --}}
	        @if(Session::has('userGroup')
		        AND (Session::get('userGroup') == 'marketing'
		        OR Session::get('userGroup') == 'clubleitung'))
				<div class="pull-right hidden-print">
					<small><a href="#" class="hide-event">Ausblenden</a></small>
				</div>
			@endif

		</div>

</div>
	  

