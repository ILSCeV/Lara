<span data-toggle="tooltip" title="{{trans('mainLang.shiftsInOwnClub')}}">
    {{$info->inOwnClub}}
</span>
<span data-toggle="tooltip" title="{{trans('mainLang.shiftsInOtherClubs')}}" style="color: lightgrey">
    {{ $info->inOtherClubs > 0 ? '+ ' . $info->inOtherClubs : '' }}
</span>
