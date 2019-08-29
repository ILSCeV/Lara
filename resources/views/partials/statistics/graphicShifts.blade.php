@php
    /**
     * @var \Lara\StatisticsInformation $info
     */
@endphp
<div class="progress text-center" style="height: 20px;">
    {{-- Progress bar for shifts in own club --}}
    <div class="progress-bar text-dark" style="width: {{$info->shifts_percent_intern}}%;" role="progressbar"
         data-toggle="tooltip" aria-valuenow="{{$info->shifts_percent_intern}}" aria-valuemin="0" aria-valuemax="100"
         title="{{trans('mainLang.shiftsInOwnSection')}}">{{$info->shifts_percent_intern}}%</div>
    {{-- Progress bar for shifts in other club (marked in grey) --}}
    <div class="progress-bar text-light"
         style="width: {{$info->shifts_percent_extern}}%; background-color: lightskyblue;" role="progressbar"
         data-toggle="tooltip" aria-valuenow="{{$info->shifts_percent_extern}}" aria-valuemin="0" aria-valuemax="100"
         title="{{trans('mainLang.shiftsInOtherSection')}}">
        {{$info->shifts_percent_extern}}%
    </div>
    <div class="progress-bar" role="progressbar"
         style="width: {{100 - $info->shifts_percent_extern - $info->shifts_percent_intern}}%; background-color: gainsboro;">
    </div>
</div>
