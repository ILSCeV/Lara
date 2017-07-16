<div class="panel panel-default">
	{{--Check if the event is still going on--}}
		@if( strtotime($clubEvent->evnt_date_end.' '.$clubEvent->evnt_time_end) < time() )
			{{-- The event is already over --}}
				<div class="panel panel-heading past-event">
		@else
			<div class="panel panel-heading">
		@endif

		<h4 class="panel-title">
			<i class="fa fa-eye-slash">&nbsp;&nbsp;</i><span class="name">{{ trans('mainLang.internalEventP') }}</span>
		</h4>
		
		{{ utf8_encode(strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start))) }} 
		&nbsp;
		DV: {{ date("H:i", strtotime($clubEvent->getSchedule->schdl_time_preparation_start)) }}
		&nbsp;
		<i class="fa fa-clock-o"></i> {{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
		-
		{{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
		&nbsp;
		<i class="fa fa-map-marker">&nbsp;</i>{{ $clubEvent->section->plc_title }}
	</div>
	<div class="panel-body">
		{{ trans('mainLang.moreDetailsAfterLogInMessage') }} {{-- <br /> --}}
        {{-- {{ trans('mainLang.moreDetailsAfterLogInMessage2') }}  --}}
	</div>
</div>
	  

