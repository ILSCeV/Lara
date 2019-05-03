
<div class="card p-0">
    <div class="card-header ">
        <h4 class="card-title p-3">
            {{ trans('mainLang.leaderBoards') }}
        </h4>
    </div>
    <ul class="nav nav-tabs">
        <li class="leaderboardsClubPicker nav-item">
            <a class="nav-link" aria-expanded="true" href="#all-leaderboards" data-toggle="tab">{{ trans('mainLang.allClubs') }}</a>
        </li>
        @foreach($clubInfos->sortKeys()->keys() as $title)
            <li class="{{Lara\Section::current()->title == $title? 'active': ''}} leaderboardsClubPicker nav-item">
                <a aria-expanded="{{Lara\Section::current()->title == $title? 'active': ''}}"
                   href="#{{ str_replace(' ', '-', strtolower($title)) }}-leaderboards"
                   data-toggle="tab" class="nav-link">
                    {{$title}}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div class="card card-body p-0">
    <div id="myTabContent" class="tab-content">
        @include('partials.statistics.leaderboardsOfClub', ['infos' => $infos, 'showClubName' => true, 'name' => 'all'])
        @foreach($clubInfos as $title => $clubInfo)
            @include('partials.statistics.leaderboardsOfClub', ['infos' => $clubInfo, 'showClubName' => false, 'name' => str_replace(' ', '-', strtolower($title))])
        @endforeach
    </div>
</div>
