{{-- Indicator for event's publish state --}}
@if($clubEvent->evnt_is_published==1)
    <i class="fa fa-check-square-o" 
       aria-hidden="true"
       data-toggle="tooltip" 
       data-placement="top"
       title="{{ trans('mainLang.eventIsPublished')}}"></i>
@else
    <i class="fa fa-square-o" 
       aria-hidden="true"
       data-toggle="tooltip" 
       data-placement="top"
       title="{{ trans('mainLang.eventIsUnpublished')}}"></i>
@endif