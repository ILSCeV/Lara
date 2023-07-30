@php
    /**
     * @var \Lara\StatisticsInformation $info
     */
@endphp
<span data-bs-toggle="tooltip" title="{{__('mainLang.shiftsInOwnSection')}}">
    {{$info->own_section}}
</span>
<span data-bs-toggle="tooltip" title="{{__('mainLang.shiftsInOtherSection')}}" style="color: lightgrey">
    {{ $info->other_section > 0 ? '+ ' . $info->other_section : '' }}
</span>
