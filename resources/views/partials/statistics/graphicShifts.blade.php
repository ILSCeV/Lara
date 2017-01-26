<div class="progress centered">
    {{-- Progress bar for shifts in own club --}}
    <div class="progress-bar" style="width: {{$info->shiftsPercentIntern}}%;"
         data-toggle="tooltip"
         title="{{trans('mainLang.shiftsInOwnClub')}}"></div>
    {{-- Progress bar for shifts in other club (marked in grey) --}}
    <div class="progress-bar"
         style="width: {{$info->shiftsPercentExtern}}%; background-color: lightgrey;"
         data-toggle="tooltip"
         title="{{trans('mainLang.shiftsInOtherClubs')}}">
    </div>
</div>
