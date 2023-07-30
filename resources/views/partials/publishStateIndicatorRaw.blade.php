{{-- Indicator for event's publish state --}}
@if($clubEvent->evnt_is_published==1)
    <i class="fa fa-check-square-o" 
       aria-hidden="true"
       data-bs-toggle="tooltip" 
       data-bs-placement="top"
       title="{{ __('mainLang.eventIsPublished')}}"></i>
@else
    <i class="fa fa-square-o" 
       aria-hidden="true"
       data-bs-toggle="tooltip" 
       data-bs-placement="top"
       title="{{ __('mainLang.eventIsUnpublished')}}"></i>
@endif
