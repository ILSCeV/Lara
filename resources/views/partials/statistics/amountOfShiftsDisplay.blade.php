@php
    /**
     * @var \Lara\StatisticsInformation $info
     */
@endphp
<span data-toggle="tooltip" title="{{trans('mainLang.shiftsInOwnSection')}}">
    {{$info->own_section}}
</span>
<span data-toggle="tooltip" title="{{trans('mainLang.shiftsInOtherSection')}}" style="color: lightgrey">
    {{ $info->other_section > 0 ? '+ ' . $info->other_section : '' }}
</span>
