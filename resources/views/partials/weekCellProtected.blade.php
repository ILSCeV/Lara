<div class="card border-warning">

	{{-- Check if the event is still going on --}}
    @php
        $classString = "card-header";
        $clubEventClass = \Lara\utilities\ViewUtility::getEventPaletteClass($clubEvent);
    @endphp
	{{-- Set card color --}}
    <div class="{{$classString}} {{$clubEventClass}}" >
			<h4 class="card-title">
				@include("partials.event-marker")
				&nbsp;
				<a class="{{$clubEventClass}}" href="{{ URL::route('event.show', $clubEvent->id) }}">
					<span class="name">{{ $clubEvent->evnt_title }}</span>
				</a>
			</h4>

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
		        <br />
		    </div>
		@endif

		<div class="card-body no-padding">

			@if (!is_null($clubEvent->getSchedule))

				{{-- Show shifts --}}
				@foreach($shifts = $clubEvent->getSchedule->shifts as $shift)
                    <div class="row">
                        @include('partials.shifts.takeShiftBar',['shift'=>$shift,'hideComments'=>true])
                    </div>
                    <div class="w-100"></div>
				@endforeach

			@endif

		</div>

</div>
