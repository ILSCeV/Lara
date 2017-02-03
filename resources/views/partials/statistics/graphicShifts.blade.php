<div class="progress centered">
    {{-- Progress bar for shifts in own club --}}
    <div class="progress-bar" style="width: {{$info->shiftsPercentIntern}}%;"
         data-toggle="tooltip"
         title="{{trans('mainLang.shiftsInOwnSection')}}"></div>
    {{-- Progress bar for shifts in other club (marked in grey) --}}
    <div class="progress-bar"
         style="width: {{$info->shiftsPercentExtern}}%; background-color: lightskyblue;"
         data-toggle="tooltip"
         title="{{trans('mainLang.shiftsInOtherSection')}}">
    </div>
    <div class="progress-bar"
         style="width: {{100 - $info->shiftsPercentExtern - $info->shiftsPercentIntern}}%; background-color: gainsboro;">
    </div>
</div>
