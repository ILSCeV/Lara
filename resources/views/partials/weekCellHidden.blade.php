<div class="card bg-body-tertiary rounded">
	{{-- Check if the event is still going on and set card color --}}
    <div class="card-header rounded-top palette-Grey-500 bg white-text @if(strtotime($clubEvent->evnt_date_end.' '.$clubEvent->evnt_time_end) < time()) past-event @endif">
		<h4 class="card-title">
			<i class="fa fa-eye-slash">&nbsp;&nbsp;</i><span class="name">{{ __('mainLang.internalEventP') }}</span>
		</h4>

		{{ utf8_encode(strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start))) }}
		&nbsp;
		DV: {{ date("H:i", strtotime($clubEvent->getSchedule->schdl_time_preparation_start)) }}
		&nbsp;
		<i class="far fa-clock"></i> {{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
		-
		{{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
		&nbsp;
		<i class="fa-solid  fa-map-marker">&nbsp;</i>{{ $clubEvent->section->title }}
	</div>

	<div class="card-body rounded-bottom">
		{{ __('mainLang.moreDetailsAfterLogInMessage') }}
	</div>
</div>


