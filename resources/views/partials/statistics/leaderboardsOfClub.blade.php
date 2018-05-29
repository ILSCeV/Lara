<div class="tab-pane fade in {{ Lara\Section::current()->title === $name? 'active': '' }}" id="{{$name}}Leaderboards">
    <table class="table table-hover">
        <thead>
        <tr>
            <td>{{trans('mainLang.name')}}</td>
            @if ($showClubName)
                <td>{{trans('mainLang.club')}}</td>
            @endif
            <td>{{trans('mainLang.totalShifts')}}</td>
        </tr>
        </thead>
        <tbody>
        {{-- Only show Top 10 Shifts --}}
        @foreach($infos->sortByDesc('inOwnClub')->take(10) as $info)
            @php
                $user = $info->user->user;
            @endphp
            <tr class=" {{Auth::user()->id === $info->user->user->id ? 'my-shift' : ''}}">
                <td>
                    @include('partials.personStatusMarker', ['status' => $user->status, 'section' => $user->section])
                    <span data-toggle="tooltip" data-placement="top" title="{{ $user->fullName() }}" >
                        {{$user->name }}
                    </span>
                </td>
                @if ($showClubName)
                    <td>
                        {{$info->userClub->clb_title }}
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
