<div class="tab-pane fade in {{ Session::get('userClub') === $title? 'active': '' }}" id="{{$name}}Leaderboards">
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
        @foreach($infos->sortByDesc('totalShifts')->take(10) as $info)
            <tr class=" {{$info->user->isLoggedInUser() ? 'my-shift' : ''}}">
                <td>
                    {{$info->user->prsn_name }}
                </td>
                @if ($showClubName)
                    <td>
                        {{$info->userClub->clb_title }}
                    </td>
                @endif
                <td>
                    {{$info->totalShifts}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
