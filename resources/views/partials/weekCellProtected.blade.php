<div class="panel panel-warning">

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
					@include("partials.event-marker", $clubEvent)&nbsp;<span class="name">{{{ $clubEvent->evnt_title }}}</span>
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

		{{-- Show password input if schedule needs one --}}
		@if( $clubEvent->getSchedule->schdl_password != '')
		    <div class="panel panel-heading hidden-print">
		        {!! Form::password('password' . $clubEvent->getSchedule->id, array('required', 
		                                             'class'=>'col-md-12 col-xs-12 black-text',
		                                             'id'=>'password' . $clubEvent->getSchedule->id,
		                                             'placeholder'=>'Passwort hier eingeben')) !!}
		        <br />
		    </div> 
		@endif 

		<div class="panel panel-body no-padding">

			@if (!is_null($clubEvent->getSchedule))	

				{{-- Show schedule entries --}}
				@foreach($entries = $clubEvent->getSchedule->getEntries as $entry)
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
				        <div class="col-xs-4 col-md-4 padding-right-minimal">
				            @include("partials.scheduleEntryTitle")
				        </div>

				        {{-- if entry occupied by member and the user is not logged in - show only the info without inputs --}}
				        @if(isset($entry->getPerson->prsn_ldap_id))

				        	{{-- ENTRY STATUS, USERNAME, DROPDOWN USERNAME and LDAP ID --}}
				            <div class="col-xs-4 col-md-4 input-append btn-group padding-left-minimal">

							    <div class="col-xs-2 col-md-2 no-padding" id="clubStatus{{ $entry->id }}">
							        @include("partials.scheduleEntryStatus")
							    </div>

							    <div class="col-xs-10 col-md-10 no-padding" id="{!! 'userName' . $entry->id !!}" >
							        {!! $entry->getPerson->prsn_name !!}
							    </div>

							    {{-- no need to show LDAP ID or TIMESTAMP in this case --}}

							</div>

							{{-- ENTRY CLUB --}}
							<div id="{!! 'club' . $entry->id !!}" class="col-xs-3 col-md-3 no-padding">
							    {!! "(" . $entry->getPerson->getClub->clb_title . ")" !!}
							</div>

							{{-- ENTRY COMMENT --}}
							{{-- Show only the icon first --}}
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
							<div id="{!! 'comment' . $entry->id !!}"
							     class="col-xs-10 col-md-10 hidden-print hide col-md-offset-1 word-break">
							    {!! !empty($entry->entry_user_comment) ? $entry->entry_user_comment : "-" !!}
							</div>

				        @else

				            {{-- ENTRY STATUS, USERNAME, DROPDOWN USERNAME and LDAP ID --}}
				            <div class="col-xs-4 col-md-4 input-append btn-group padding-left-minimal">      
				                @include("partials.scheduleEntryName")
				            </div> 

				            {{-- ENTRY CLUB and DROPDOWN CLUB --}}
				            <div class="col-xs-3 col-md-3 no-padding">
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

				        @endif    
				            
				        {!! Form::submit( 'save', array('id' => 'btn-submit-changes' . $entry->id, 'hidden') ) !!}
				        {!! Form::close() !!}

				    </div>

				    {{-- Show a line after each row except the last one --}}
					@if($entry !== $entries->last() ) 
						<hr class="col-md-12 col-xs-12 top-padding no-margin">
					@endif

				@endforeach

			@endif
			
		</div>

</div>
	  

