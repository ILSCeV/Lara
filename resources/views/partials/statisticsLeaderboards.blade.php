<div class="centered">
    <h2>
        {{ trans('mainLang.leaderBoards') }}
    </h2>
    <ul class="nav nav-tabs">
        <li><a aria-expanded="true" href="#allLeaderboards"
               data-toggle="tab">All</a></li>
        @foreach($clubInfos->keys() as $title)
            <li class="{{Session::get('userClub') == $title? 'active': ''}}"><a
                        aria-expanded="{{Session::get('userClub') == $title? 'active': ''}}" href="#{{$title}}Leaderboards"
                        data-toggle="tab">{{$title}}</a></li>
        @endforeach
    </ul>
    <div id="myTabContent" class="tab-content">
        @include('partials.statistics.leaderboardsOfClub', ['infos' => $infos, 'showClubName' => true, 'name' => 'all'])
        @foreach($clubInfos as $title => $clubInfo)
            @include('partials.statistics.leaderboardsOfClub', ['infos' => $clubInfo, 'showClubName' => false, 'name' => $title])
        @endforeach
    </div>
</div>
