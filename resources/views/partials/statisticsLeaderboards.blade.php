
<div class="panel panel-heading no-padding">
    <h4 class="panel-title all-sides-padding-16">
        {{ trans('mainLang.leaderBoards') }}
    </h4>

    <ul class="nav nav-tabs">
        <li class="leaderboardsClubPicker">
            <a aria-expanded="true" href="#allLeaderboards" data-toggle="tab">{{ trans('mainLang.allClubs') }}</a>
        </li>
        @foreach($clubInfos->keys() as $title)
            <li class="{{Session::get('userClub') == $title? 'active': ''}} leaderboardsClubPicker">
                <a aria-expanded="{{Session::get('userClub') == $title? 'active': ''}}" 
                   href="#{{$title}}Leaderboards"
                   data-toggle="tab">
                    {{$title}}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div class="panel panel-body no-padding">
    <div id="myTabContent" class="tab-content">
        @include('partials.statistics.leaderboardsOfClub', ['infos' => $infos, 'showClubName' => true, 'name' => 'all'])
        @foreach($clubInfos as $title => $clubInfo)
            @include('partials.statistics.leaderboardsOfClub', ['infos' => $clubInfo, 'showClubName' => false, 'name' => $title])
        @endforeach
    </div>
</div>