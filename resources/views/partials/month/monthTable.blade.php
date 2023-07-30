{{-- Month Table --}}
<div class="col-12 col-md-12 calendarWrapper p-0">
    <div class="d-none">
        <div class="calendarWeek noBorderTop" style="border-top: 0px">
            {{ __('mainLang.Cw') }}
        </div>
        <div class="weekDay ps-2">
            {{ __('mainLang.Mo') }}
        </div>
        <div class="weekDay ps-2">
            {{ __('mainLang.Tu') }}
        </div>
        <div class="weekDay ps-2">
            {{ __('mainLang.We') }}
        </div>
        <div class="weekDay ps-2">
            {{ __('mainLang.Th') }}
        </div>
        <div class="weekDay ps-2">
            {{ __('mainLang.Fr') }}
        </div>
        <div class="weekDay ps-2">
            {{ __('mainLang.Sa') }}
        </div>
        <div class="weekDay ps-2">
            {{ __('mainLang.Su') }}
        </div>
    </div>

    <div class="calendarRow clearfix group noHeightMobile d-none">
        <!--This is an empty row-->
        <!--Without this row, the first real calendar row would disapear-->
    </div>
    {{--Print Weeks on left side--}}
    @foreach($mondays as $weekStart)
        {{-- Add one week to the week start to get the next monday --}}
        @php $weekEnd = new DateTime($weekStart->format('Y-m-d')); $weekEnd->modify('+1 week') @endphp
        @if ($weekStart->format('W/o') === date('W/o'))
            {{-- Current week --}}
            <div class="calendarRow clearfix group bg-dark-subtle" >
                <div class="calendarWeek bg-dark-subtle">
                    <a href="{!! Request::getBasePath() !!}/calendar/{{(new DateTime($weekStart->format('Y-m-d')))->modify('+3 days')->format('Y\/\K\WW')}}"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top"
                       title="{{ __('mainLang.showWeek')}}">
                        <span class="onlyOnMobile">{{ __('mainLang.Cw') }}</span> {{$weekStart->format('W')}}.
                    </a>
                </div>
                {{-- Foreach on DatePeriod excludes the last day, so we iterate over Monday to Sunday--}}
                @foreach(new DatePeriod($weekStart, new DateInterval('P1D'), $weekEnd) as $weekDay)
                    @include('partials.month.day', ['month' => $date['month']])
                @endforeach
            </div>
        @else
            {{-- Not current week --}}
            <div class="calendarRow clearfix group">
                <div class="calendarWeek ">
                    <a href="{!! Request::getBasePath() !!}/calendar/{{(new DateTime($weekStart->format('Y-m-d')))->modify('+3 days')->format('Y\/\K\WW')}}"
                       data-bs-toggle="tooltip"
                       data-bs-placement="top"
                       title="{{ __('mainLang.showWeek')}}">
                        <span class="onlyOnMobile">{{ __('mainLang.Cw') }}</span> {{$weekStart->format('W')}}.
                    </a>
                </div>
                @foreach(new DatePeriod($weekStart, new DateInterval('P1D'), $weekEnd) as $weekDay)
                    @include('partials.month.day', ['month' => $date['month']])
                @endforeach
            </div>
        @endif
    @endforeach
    <hr class="py-0 my-0">
</div>
