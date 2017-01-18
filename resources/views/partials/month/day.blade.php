@if ($weekDay->format('Y-m-d') === date('Y-m-d'))
    {{-- The actual date of today marked in dark gray--}}
    <div class="thisMonth today-marker-today custom-md-85">
@elseif ($weekDay->format('W') === date('W'))
    {{-- Other day in this week --}}
    <div class="thisMonth today-marker custom-md-85">
@elseif ($weekDay->format('m') === $month)
    <div class="thisMonth custom-md-85">
@else
    <div class="otherMonth custom-md-85">
@endif
    <div class="cell10 padleft">
        @if(Session::has('userGroup'))
            <a href="{{ Request::getBasePath() }}/event/{{ strftime("%Y/%m/%d", $weekDay->getTimestamp()) }}/0/create"
               data-toggle="tooltip" 
               data-placement="top"
               title="{{ trans('mainLang.createEventOnThisDate')}}">
                {{$weekDay->format('d')}}
            </a>
        @else
            {{$weekDay->format('d')}}
        @endif
        <small class="visible-xs visible-md visible-sm">
            {{-- Display day of the month--}}
            {{ strftime("%a", $weekDay->getTimestamp()) }}
        </small>
    </div>
    <div class="cell90">
        @include( 'partials.month.monthCell')
    </div>
</div>
