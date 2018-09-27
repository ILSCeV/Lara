<div class="card bg-light">
	{{-- Check if the event is still going on and set card color --}}
	@if( strtotime($clubEvent->evnt_date_end.' '.$clubEvent->evnt_time_end) < time() )
		<div class=" card-header palette-Grey-500 bg white-text past-event">
	@else
		<div class=" card-header palette-Grey-500 bg white-text">
	@endif

		<h4 class="card-title">
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
		<i class="fa fa-map-marker">&nbsp;</i>{{ $clubEvent->section->title }}
	</div>

	<div class="card-body">
		{{ trans('mainLang.moreDetailsAfterLogInMessage') }} 
	</div>
</div>
	  

