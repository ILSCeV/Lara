
<div class="panel panel-heading no-padding">
    <h4 class="panel-title all-sides-padding-16">
        {{ trans('mainLang.leaderBoards') }}
    </h4>

    <ul class="nav nav-tabs">
        <li class="leaderboardsClubPicker">
            <a aria-expanded="true" href="#all-leaderboards" data-toggle="tab">{{ trans('mainLang.allClubs') }}</a>
        </li>
        @foreach($clubInfos->keys() as $title)
            <li class="{{Lara\Section::current()->title == $title? 'active': ''}} leaderboardsClubPicker">
                <a aria-expanded="{{Lara\Section::current()->title == $title? 'active': ''}}"
                   href="#{{ str_replace(' ', '-', strtolower($title)) }}-leaderboards"
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
            @include('partials.statistics.leaderboardsOfClub', ['infos' => $clubInfo, 'showClubName' => false, 'name' => str_replace(' ', '-', strtolower($title))])
        @endforeach
    </div>
</div>
