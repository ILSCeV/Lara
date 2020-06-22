<div class="card border-light rounded-top">

	{{--Check if the event is still going on--}}
    @php
        /** @var \Lara\ClubEvent $clubEvent */
        $createPersonLdapId = $clubEvent->creator?$clubEvent->creator->person->prsn_ldap_id:null;
        $isAllowedToSee = \Auth::hasUser() && \Auth::user()->hasPermissionsInSection($clubEvent->section,\Lara\utilities\RoleUtility::PRIVILEGE_CL) || Lara\Person::isCurrent($createPersonLdapId) ;
        $isUnBlocked = is_null($clubEvent->unlock_date) || \Carbon\Carbon::now()->greaterThanOrEqualTo($clubEvent->unlock_date)  || $isAllowedToSee;
        $classString = "card-header rounded";
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
		@if($isUnBlocked && $clubEvent->getSchedule->schdl_password != '')
		    <div class="{{ $classString }} hidden-print">
		        {!! Form::password('password' . $clubEvent->getSchedule->id, ['required',
		                                             'class'=>'col-md-12 col-12 black-text',
		                                             'id'=>'password' . $clubEvent->getSchedule->id,
		                                             'placeholder'=>Lang::get('mainLang.enterPasswordHere')]) !!}
		        <br/>
		    </div>
		@endif

		<div class="card-body p-0">
          @if($isUnBlocked)
			{{-- Show shifts --}}
            @include('partials.shifts.takeShiftTable',['shifts' => $clubEvent->getSchedule->shifts,'hideComments'=>true, 'commentsInSeparateLine' => true])
          @else
              <div class="text-center">
                {{trans('mainLang.availableAt')}}  {{$clubEvent->unlock_date->isoFormat('DD.MM.YYYY hh:mm')}}
              </div>
          @endif

		</div>
	@include('partials.weekView.hideEvent')
</div>
