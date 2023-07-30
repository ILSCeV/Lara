<div class="tab-pane fade in {{ Lara\Section::current()->title === $name? 'active': '' }}" id="{{$name}}-leaderboards">
    <table class="table table-hover">
        <thead>
        <tr>
            <td>{{__('mainLang.name')}}</td>
            @if ($showClubName)
                <td>{{__('mainLang.club')}}</td>
            @endif
            <td>{{__('mainLang.totalShifts')}}</td>
        </tr>
        </thead>
        <tbody>
        {{-- Only show Top 10 Shifts --}}
        @php
        /**
         * @var \Lara\StatisticsInformation $info
         */
        @endphp
        @foreach($infos->sortByDesc('own_section')->take(10) as $info)
            @php
                $user = $info->user()->first();
            @endphp
            <tr class=" {{Auth::user()->id === $user->id ? 'my-shift' : ''}}">
                <td>
                    @include('partials.personStatusMarker', ['status' => $user->status, 'section' => $user->section])
                    <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $user->fullName() }}" >
                        {{$user->name }}
                    </span>
                </td>
                @if ($showClubName)
                    <td>
                        {{$user->section->club()->clb_title }}
                    </td>
                @endif
                <td>
                    @include('partials.statistics.amountOfShiftsDisplay')
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
