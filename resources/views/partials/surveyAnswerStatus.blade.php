@if($answer->getPerson)
    @php
        $person = $answer->getPerson;
        $attributes = Lara\Status::style($person->prsn_status);
        $section = $person->club->section();
    @endphp
    @if ($section)
        <i class="{{$attributes["status"] }}"
           name="status-icon"
           style=" {{ $attributes["style"] }}"
           {{--data-toggle="tooltip"--}}
           {{--data-placement="top"--}}
           title="{{ Lara\Status::localize($person->prsn_status, $section) }}"></i>
    @else
        <i class="fa fa-circle"
           style="color:lightgrey;"
           {{--data-toggle="tooltip"--}}
           {{--data-placement="top"--}}
           title="{{ trans('mainLang.external') }}"></i>
    @endif
@else
    <i class="fa fa-circle"
       name="status-icon"
       style="color:lightgrey;"
       {{--data-toggle="tooltip"--}}
       {{--data-placement="top"--}}
       title="{{ trans('mainLang.external') }}"></i>
@endif
