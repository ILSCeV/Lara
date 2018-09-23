<div class="card section-filter {!! "section-" . $clubEvent->section->id !!}">
    {{-- show only a placeholder for private events --}}
    @if($clubEvent->evnt_is_private && !Auth::user())
        <div class="card card-header">
            <h4><span class="name">{{ trans('mainLang.internalEvent') }}</span></h4>
        </div>
    @else
        {{-- Set card color --}}
        @if     ($clubEvent->evnt_type == 0)
            <div class="card card-header palette-{!! $clubEvent->section->color !!}-700 bg">
        @elseif ($clubEvent->evnt_type == 1)
            <div class="card card-header palette-Purple-500 bg">
        @elseif ($clubEvent->evnt_type == 2
              || $clubEvent->evnt_type == 3
              || $clubEvent->evnt_type == 10
              || $clubEvent->evnt_type == 11)
            <div class="card card-header palette-{!! $clubEvent->section->color !!}-900 bg">
        @elseif ($clubEvent->evnt_type == 4
              || $clubEvent->evnt_type == 5
              || $clubEvent->evnt_type == 6)
            <div class="card card-header palette-{!! $clubEvent->section->color !!}-500 bg">
        @elseif ($clubEvent->evnt_type == 7
              || $clubEvent->evnt_type == 8)
            <div class="card card-header palette-{!! $clubEvent->section->color !!}-300 bg">
        @elseif ($clubEvent->evnt_type == 9)
            <div class="card card-header palette-{!! $clubEvent->section->color !!}-500 bg">
        @endif
            <h4 class="card-title">
                @include("partials.event-marker")&nbsp;<a href="{{ URL::route('event.show', $clubEvent->id) }}">{{ $clubEvent->evnt_title }}</a>
            </h4>
            <h5 class="card-title">{{ $clubEvent->evnt_subtitle }}</h5>
        </div>
	@endif

	<div class="card card-body no-margin">
		<strong>{{ trans('mainLang.begin') }}:</strong> {{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_start)) }}
		um {{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
		<br />
		<strong>{{ trans('mainLang.end') }}:</strong> {{ strftime("%a, %d. %b", strtotime($clubEvent->evnt_date_end)) }}
		um {{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
		<br />
		<strong>{{ trans('mainLang.club') }}:</strong> {{ $clubEvent->section->title }}
	</div>
</div>
