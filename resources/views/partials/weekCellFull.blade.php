<div class="card bg-warning">

	{{--Check if the event is still going on--}}
    @php
        $classString = "card-header";
        $clubEventClass = \Lara\utilities\ViewUtility::getEventPaletteClass($clubEvent);
    @endphp

    @if( strtotime($clubEvent->evnt_date_end.' '.$clubEvent->evnt_time_end) < time() )
        {{-- The event is already over --}}
        <?php $classString .= " past-event";?>
    @endif

	{{-- Set card color --}}
           <div class="{{$classString}} {{$clubEventClass}}" >
			<h4 class="card-title ">
				@include("partials.event-marker")
				&nbsp;
				<a class="{{$clubEventClass}}" href="{{ URL::route('event.show', $clubEvent->id) }}">
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
			<i class="far fa-clock"></i> {{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
			-
			{{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
			&nbsp;
			<i class="fas fa-map-marker">&nbsp;</i>{{ $clubEvent->section->title }}

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

		<div class="card-body no-padding">

			{{-- Show shifts --}}
			@foreach($shifts = $clubEvent->getSchedule->shifts as $shift)
				{{-- highlight with my-shift class if the signed in user is the person to do the shift --}}
                {{-- add a divider if the shift is not the last one --}}
			    <div class="row {!! $shift !== $shifts->last() ? ' divider': false !!}{!! ( isset($shift->getPerson->prsn_ldap_id) && Auth::user() && $shift->getPerson->prsn_ldap_id == Auth::user()->person->prsn_ldap_id) ? " my-shift" : false !!}">
			        {!! Form::open(  array( 'route' => ['shift.update', $shift->id],
			                                'id' => $shift->id,
			                                'method' => 'put',
			                                'class' => 'shift form-inline')  ) !!}

			        {{-- SPAMBOT HONEYPOT - this field will be hidden, so if it's filled, then it's a bot or a user tampering with page source --}}
			        <div id="welcome-to-our-mechanical-overlords">
			            <small>If you can read this this - refresh the page to update CSS styles or switch CSS support on.</small>
			            <input type="text" id="{!! 'website' . $shift->id !!}" name="{!! 'website' . $shift->id !!}" value="" />
			        </div>

			        {{-- Shift TITLE --}}
			        <div class="col-2 padding-right-minimal">
			            @include("partials.shiftTitle")
			        </div>

			        {{-- SHIFT STATUS, USERNAME, DROPDOWN USERNAME and LDAP ID --}}
					<div class="col-5 input-append btn-group padding-left-minimal">
					    @include("partials.shiftName")
					</div>

					{{-- SHIFT CLUB and DROPDOWN CLUB --}}
					<div class="col-4 no-padding">
					    @include("partials.shiftClub")
					</div>

					{{-- SMALL COMMENT ICON --}}
					<div class="col-1 no-padding">
				        @if( $shift->comment == "" )
				            <button type="button" class="showhide btn-small btn-secondary hidden-print" data-dismiss="alert">
				                <i class="far fa-comment-alt"></i>
				            </button>
				        @else
				            <button type="button" class="showhide btn-small btn-secondary hidden-print" data-dismiss="alert">
				                <i class="fas fa-comment"></i>
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
					                     'class'=>'col-10 hidden-print hide offset-2 offset-2 word-break' ))
					!!}

			        {!! Form::submit( 'save', array('id' => 'btn-submit-changes' . $shift->id, 'hidden') ) !!}
			        {!! Form::close() !!}
			    </div>

			@endforeach

			{{-- Show a "hide" button for management, that allows removal of an event from current view - needed for printing --}}
	        @is('marketing', 'clubleitung', 'admin')
		        <hr class="col-12 top-padding no-margin no-padding">
				<div class="padding-right-16 bottom-padding float-right hidden-print">
					<small><a href="#" class="hide-event">{{ trans('mainLang.hide') }}</a></small>
				</div>
			@endis

		</div>

</div>
