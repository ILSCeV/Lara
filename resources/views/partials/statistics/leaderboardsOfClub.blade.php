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
            <tr class=" {{Auth::user()->id === $info->user->user()->id ? 'my-shift' : ''}}">
                @php
                    $user = $info->user->user();
                @endphp
                <td>
                    @include('partials.personStatusMarker', ['status' => $user->status, 'section' => $user->section])
                    <div data-toggle="tooltip" data-placement="top" title="{{ Gate::allows('accessInformation', $user) ? $user->fullName() : "" }}" style="display:inline">
                        {{$user->name }}
                    </div>
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
