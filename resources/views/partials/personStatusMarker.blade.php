@if (!empty($status))
    @php
        $attributes = Lara\Status::style($status)
    @endphp
    <i class="{{ $attributes["status"] }}"
       name="status-icon"
       style="{{ $attributes["style"] }}"
       data-toggle="tooltip"
       data-placement="top"
       title="{{ Lara\Status::localize($status, $section)}}"></i>
@else
    <i class="fas fa-circle"
       name="status-icon"
       style="color:lightgrey;"
       data-toggle="tooltip"
       data-placement="top"
       title="{{ trans('mainLang.external') }}"></i>
@endif
