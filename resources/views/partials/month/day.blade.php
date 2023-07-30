@if ($weekDay->format('Y-m-d') === date('Y-m-d'))
    {{-- The actual date of today marked in dark gray--}}
    <div class="bg-primary-subtle scroll-marker custom-md-85">
@elseif ($weekDay->format('W/o') === date('W/o'))
    {{-- Other day in this week --}}
    <div class="bg-dark-subtle custom-md-85">
@elseif ($weekDay->format('m') === $month)
    <div class="custom-md-85">
@else
    <div class="custom-md-85">
@endif
    <div class="day-cell">
        <small>
            {{-- Display day of the week--}}
            {{ strftime("%a", $weekDay->getTimestamp()) }}
        </small>
        @auth
            <a href="{{ Request::getBasePath() }}/event/{{ strftime("%Y/%m/%d", $weekDay->getTimestamp()) }}/0/create"
               data-bs-toggle="tooltip" 
               data-bs-placement="top"
               title="{{ __('mainLang.createEventOnThisDate')}}">
                {{$weekDay->format('d')}}
            </a>
        @else
            {{$weekDay->format('d')}}
        @endauth

    </div>
    <div class="day-cell-events">
        @include( 'partials.month.monthCell')
    </div>
</div>
