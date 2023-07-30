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
           {{--data-bs-toggle="tooltip"--}}
           {{--data-bs-placement="top"--}}
           title="{{ Lara\Status::localize($person->prsn_status, $section) }}"></i>
    @else
        <i class="fa fa-circle"
           style="color:lightgrey;"
           {{--data-bs-toggle="tooltip"--}}
           {{--data-bs-placement="top"--}}
           title="{{ __('mainLang.external') }}"></i>
    @endif
@else
    <i class="fa fa-circle"
       name="status-icon"
       style="color:lightgrey;"
       {{--data-bs-toggle="tooltip"--}}
       {{--data-bs-placement="top"--}}
       title="{{ __('mainLang.external') }}"></i>
@endif
