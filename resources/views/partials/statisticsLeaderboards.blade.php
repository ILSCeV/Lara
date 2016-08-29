<div class="centered">
    <h2>
        {{ trans('mainLang.leaderBoards') }}
    </h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <td>{{trans('mainLang.name')}}</td>
                <td>{{trans('mainLang.club')}}</td>
                <td>{{trans('mainLang.totalShifts')}}</td>
            </tr>
        </thead>
        <tbody>
        {{-- Only show Top 10 Shifts --}}
        @foreach($infos->sortByDesc('totalShifts')->take(10) as $info)
            <tr>
                <td>
                    {{$info->user->prsn_name }}
                </td>
                <td>
                    {{$info->userClub->clb_title }}
                </td>
                <td>
                    {{$info->totalShifts}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
