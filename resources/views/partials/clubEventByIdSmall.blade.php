<div class="card section-filter {!! "section-" . $clubEvent->section->id !!} w-100 ">
    {{-- show only a placeholder for private events --}}
    @if($clubEvent->evnt_is_private && !Auth::user())
        <div class="card-header {{\Lara\utilities\ViewUtility::getEventPaletteClass($clubEvent)}}">
            <h4><span class="name">{{ trans('mainLang.internalEvent') }}</span></h4>
        </div>
    @else
        {{-- Set card color --}}
        <div class="card-header {{\Lara\utilities\ViewUtility::getEventPaletteClass($clubEvent)}}">
            <h4 class="card-title">
                @include("partials.event-marker")&nbsp;<a class="{{\Lara\utilities\ViewUtility::getEventPaletteClass($clubEvent)}}" href="{{ URL::route('event.show', $clubEvent->id) }}">{{ $clubEvent->evnt_title }}</a>
            </h4>
            <h5 class="card-title">{{ $clubEvent->evnt_subtitle }}</h5>
        </div>
	@endif

	<div class="card-body">
		<strong>{{ trans('mainLang.begin') }}:</strong> {{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start)) }}
		um {{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
		<br />
		<strong>{{ trans('mainLang.end') }}:</strong> {{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_end)) }}
		um {{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
		<br />
		<strong>{{ trans('mainLang.club') }}:</strong> {{ $clubEvent->section->title }}
	</div>
</div>
