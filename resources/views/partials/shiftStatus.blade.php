{{ Form::button('<i class="fas fa-check"
                   data-toggle="tooltip"
                   data-placement="top"
                   title="Änderungen speichern"></i>',
                array('type' => 'submit',
                      'name' => 'btn-submit-change' . $shift->id,
                      'id' => 'btn-submit-changes' . $shift->id,
                      'class' => 'btn btn-sm btn-success hide')) }}

@if( is_null($shift->getPerson) )

    <i class="fas fa-question"
       name="status-icon"
       style="color:lightgrey;"
       data-toggle="tooltip"
       data-placement="top"
       title="{{ trans('mainLang.jobFree') }}"></i>

@else

    @php
    /** @var \Lara\Shift $shift */
        $person = $shift->person;
        $attributes = Lara\Status::style($person->prsn_status);
        $section = !is_null($person->club)?$person->club->section():null;
    @endphp

    @if ($section)
        <i class="{{ $attributes["status"]}}"
           name="status-icon"
           style="{{ $attributes["style"] }}"
           data-toggle="tooltip"
           data-placement="top"
           title="{{ Lara\Status::localize($person->prsn_status, $section) }}"></i>
    @else
        <i class="far fa-circle"
           name="status-icon"
           style="color:yellowgreen;"
           data-toggle="tooltip"
           data-placement="top"
           title="{{ trans('mainLang.external') }}"></i>
    @endif

@endif
