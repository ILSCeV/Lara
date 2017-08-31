@if(Session::has("userId") || ! $clubEvent->evnt_is_private)
    @include("partials.event-marker", $clubEvent)
    {{--

    Disabling iCal until fully functional.

    @if(Session::has("userId")
        @include("partials.publishStateIndicator")
    @endif

    --}}
    &nbsp;&nbsp;
    <a href="{{ URL::route('event.show', $clubEvent->id) }}"
       data-toggle="tooltip"
       data-placement="right"
       title="{{ trans('mainLang.showDetails')}}">
        {{ $clubEvent->evnt_title }}
    </a>
@else
    <i class="fa fa-eye-slash white-text"></i>
    &nbsp;&nbsp;
    <span class="white-text">{{ trans('mainLang.internEventP') }}</span>
@endif
