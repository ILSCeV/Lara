<div class="progress text-center">
    {{-- Progress bar for shifts in own club --}}
    <div class="progress-bar" style="width: {{$info->shiftsPercentIntern}}%;"
         data-toggle="tooltip"
         aria-valuemin="0"
         aria-valuemax="100"
         aria-valuenow="{{$info->shiftsPercentIntern}}"
         role="progressbar"
         title="{{trans('mainLang.shiftsInOwnSection')}}"></div>
    {{-- Progress bar for shifts in other club (marked in grey) --}}
    <div class="progress-bar"
         style="width: {{$info->shiftsPercentExtern}}%; background-color: lightskyblue;"
         aria-valuemin="0"
         aria-valuemax="100"
         aria-valuenow="{{$info->shiftsPercentExtern}}"
         data-toggle="tooltip"
         role="progressbar"
         title="{{trans('mainLang.shiftsInOtherSection')}}">
    </div>
    <div class="progress-bar"
         role="progressbar"
         aria-valuemin="0"
         aria-valuemax="100"
         aria-valuenow="{{100 - $info->shiftsPercentExtern - $info->shiftsPercentIntern}}"
         style="width: {{100 - $info->shiftsPercentExtern - $info->shiftsPercentIntern}}%; background-color: gainsboro;">
    </div>
</div>
