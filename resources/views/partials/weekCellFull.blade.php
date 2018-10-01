<div class="panel panel-warning">

	{{--Check if the event is still going on--}}
	<?php $classString = "panel panel-heading";?>
	@if( strtotime($clubEvent->evnt_date_end.' '.$clubEvent->evnt_time_end) < time() )
		{{-- The event is already over --}}
		<?php $classString .= " past-event";?>
	@endif

	{{-- Set panel color --}}
	@if     ($clubEvent->evnt_type == 0)
        <div class="{{$classString}} palette-{!! $clubEvent->section->color !!}-700 bg">
    @elseif ($clubEvent->evnt_type == 1)
        <div class="{{$classString}} palette-Purple-500 bg">
    @elseif ($clubEvent->evnt_type == 2
    	  || $clubEvent->evnt_type == 3
          || $clubEvent->evnt_type == 10
          || $clubEvent->evnt_type == 11)
        <div class="{{$classString}} palette-{!! $clubEvent->section->color !!}-900 bg">
    @elseif ($clubEvent->evnt_type == 4
          || $clubEvent->evnt_type == 5
          || $clubEvent->evnt_type == 6)
        <div class="{{$classString}} palette-{!! $clubEvent->section->color !!}-500 bg">
    @elseif ($clubEvent->evnt_type == 7
          || $clubEvent->evnt_type == 8)
        <div class="{{$classString}} palette-{!! $clubEvent->section->color !!}-300 bg">
    @elseif ($clubEvent->evnt_type == 9)
        <div class="{{$classString}} palette-{!! $clubEvent->section->color !!}-500 bg">
    @endif

			<h4 class="panel-title">
				@include("partials.event-marker")
				&nbsp;
				<a href="{{ URL::route('event.show', $clubEvent->id) }}">
					<span class="name">{{ $clubEvent->evnt_title }}</span>
				</a>
			</h4>

			{{--

			Disabling iCal until fully functional.

			@include('partials.publishStateIndicatorRaw')
			&nbsp;

			--}}
			{{ utf8_encode(strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start))) }}
			&nbsp;
			DV: {{ date("H:i", strtotime($clubEvent->getSchedule->schdl_time_preparation_start)) }}
			&nbsp;
			<i class="fa fa-clock-o"></i> {{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
			-
			{{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
			&nbsp;
			<i class="fa fa-map-marker">&nbsp;</i>{{ $clubEvent->section->title }}

		</div>



		{{-- Show password input if schedule needs one --}}
		@if( $clubEvent->getSchedule->schdl_password != '')
		    <div class="{{ $classString }} hidden-print">
		        {!! Form::password('password' . $clubEvent->getSchedule->id, ['required',
		                                             'class'=>'col-md-12 col-xs-12 black-text',
		                                             'id'=>'password' . $clubEvent->getSchedule->id,
		                                             'placeholder'=>Lang::get('mainLang.enterPasswordHere')]) !!}
		        <br/>
		    </div>
		@endif

		<div class="panel panel-body no-padding">

			{{-- Show shifts --}}
			@foreach($shifts = $clubEvent->getSchedule->shifts as $shift)
				{{-- highlight with my-shift class if the signed in user is the person to do the shift --}}
                {{-- add a divider if the shift is not the last one --}}
			    <div class="row{!! $shift !== $shifts->last() ? ' divider': false !!}{!! ( isset($shift->getPerson->prsn_ldap_id) && Auth::user() && $shift->getPerson->prsn_ldap_id == Auth::user()->person->prsn_ldap_id) ? " my-shift" : false !!}">
			        {!! Form::open(  array( 'route' => ['shift.update', $shift->id],
			                                'id' => $shift->id,
			                                'method' => 'put',
			                                'class' => 'shift')  ) !!}

			        {{-- SPAMBOT HONEYPOT - this field will be hidden, so if it's filled, then it's a bot or a user tampering with page source --}}
			        <div id="welcome-to-our-mechanical-overlords">
			            <small>If you can read this this - refresh the page to update CSS styles or switch CSS support on.</small>
			            <input type="text" id="{!! 'website' . $shift->id !!}" name="{!! 'website' . $shift->id !!}" value="" />
			        </div>

			        {{-- Shift TITLE --}}
			        <div class="col-xs-4 col-md-4 padding-right-minimal">
			            @include("partials.shiftTitle")
			        </div>

			        {{-- SHIFT STATUS, USERNAME, DROPDOWN USERNAME and LDAP ID --}}
					<div class="col-xs-4 col-md-4 input-append btn-group padding-left-minimal">
					    @include("partials.shiftName")
					</div>

					{{-- SHIFT CLUB and DROPDOWN CLUB --}}
					<div class="col-xs-3 col-md-3 no-padding">
					    @include("partials.shiftClub")
					</div>

					{{-- SMALL COMMENT ICON --}}
					<div class="col-xs-1 col-md-1 no-padding">
				        @if( $shift->comment == "" )
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
					{!! Form::text('comment' . $shift->id,
					               $shift->comment,
					               array('placeholder'=>Lang::get('mainLang.addCommentHere'),
					                     'id'=>'comment' . $shift->id,
					                     'name'=>'comment' . $shift->id,
					                     'class'=>'col-xs-10 col-md-10 hidden-print hide col-md-offset-1 col-xs-offset-1 word-break' ))
					!!}

			        {!! Form::submit( 'save', array('id' => 'btn-submit-changes' . $shift->id, 'hidden') ) !!}
			        {!! Form::close() !!}
			    </div>

			@endforeach

			{{-- Show a "hide" button for management, that allows removal of an event from current view - needed for printing --}}
	        @is('marketing', 'clubleitung', 'admin')
		        <hr class="col-md-12 col-xs-12 top-padding no-margin no-padding">
				<div class="padding-right-16 bottom-padding pull-right hidden-print">
					<small><a href="#" class="hide-event">{{ trans('mainLang.hide') }}</a></small>
				</div>
			@endis

		</div>

</div>
