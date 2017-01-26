<span data-toggle="tooltip" title="{{trans('mainLang.shiftsInOwnSection')}}">
    {{$info->inOwnClub}}
</span>
<span data-toggle="tooltip" title="{{trans('mainLang.shiftsInOtherSection')}}" style="color: lightgrey">
    {{ $info->inOtherClubs > 0 ? '+ ' . $info->inOtherClubs : '' }}
</span>
