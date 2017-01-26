<div class="tab-pane fade in {{ Session::get('userClub') === $name? 'active': '' }}" id="{{$name}}Leaderboards">
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
            <tr class=" {{$info->user->isLoggedInUser() ? 'my-shift' : ''}}">
                <td>
                    @include('partials.personStatusMarker', ['person' => $info->user]){{$info->user->prsn_name }}
                </td>
                @if ($showClubName)
                    <td>
                        {{$info->userClub->clb_title }}
                    </td>
                @endif
                <td>
                    {{$info->inOwnClub}} <span style="color: lightgrey">{{ $info->inOtherClubs > 0 ? '+ ' . $info->inOtherClubs : '' }}</span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
