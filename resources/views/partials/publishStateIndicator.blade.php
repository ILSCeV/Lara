{{-- Indicator for event's publish state --}}
@if($clubEvent->evnt_is_published==1)
    <i class="fa fa-check-square-o move-to-bottom-left-corner" 
       aria-hidden="true"
       data-toggle="tooltip" 
       data-placement="top"
       title="{{ trans('mainLang.eventIsPublished')}}"></i>
    <small>&nbsp;</small>
@else
    <i class="fa fa-square-o move-to-bottom-left-corner" 
       aria-hidden="true"
       data-toggle="tooltip" 
       data-placement="top"
       title="{{ trans('mainLang.eventIsUnpublished')}}"></i>
    <small>&nbsp;</small>
@endif