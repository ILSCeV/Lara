{{-- Indicator for event's publish state --}}
@if($clubEvent->evnt_is_published==1)
    <i class="fa fa-check-square-o move-to-bottom-left-corner" 
       aria-hidden="true"
       data-bs-toggle="tooltip" 
       data-bs-placement="top"
       title="{{ __('mainLang.eventIsPublished')}}"></i>
    <small>&nbsp;</small>
@else
    <i class="fa fa-square-o move-to-bottom-left-corner" 
       aria-hidden="true"
       data-bs-toggle="tooltip" 
       data-bs-placement="top"
       title="{{ __('mainLang.eventIsUnpublished')}}"></i>
    <small>&nbsp;</small>
@endif
