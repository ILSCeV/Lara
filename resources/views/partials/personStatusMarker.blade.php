@if (!empty($status))
    @php
        $attributes = Lara\Status::style($status)
    @endphp
    <i class="{{ $attributes["status"] }}"
       name="status-icon"
       style="{{ $attributes["style"] }}"
       data-bs-toggle="tooltip"
       data-bs-placement="top"
       title="{{ Lara\Status::localize($status, $section)}}"></i>
@else
    <i class="fa-solid  fa-circle"
       name="status-icon"
       style="color:lightgrey;"
       data-bs-toggle="tooltip"
       data-bs-placement="top"
       title="{{ __('mainLang.external') }}"></i>
@endif
