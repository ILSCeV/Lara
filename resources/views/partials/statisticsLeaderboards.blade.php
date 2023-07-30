<div class="card">
    <div class="card-header">
        <h4 class="card-title">
            {{ __('mainLang.leaderBoards') }}
        </h4>
        <ul class="nav nav-tabs">
            <li class="leaderboardsClubPicker nav-item">
                <a class="nav-link" aria-expanded="true" href="#all-leaderboards"
                    data-bs-toggle="tab">{{ __('mainLang.allClubs') }}</a>
            </li>
            @foreach ($clubInfos->sortKeys()->keys() as $title)
                <li class="{{ Lara\Section::current()->title == $title ? 'active' : '' }} leaderboardsClubPicker nav-item">
                    <a data-section="{{$title}}" aria-expanded="{{ Lara\Section::current()->title == $title ? 'active' : '' }}"
                        href="#{{ str_replace(' ', '-', strtolower($title)) }}-leaderboards" data-bs-toggle="tab"
                        class="nav-link">
                        {{ $title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>



    <div class="card-body">
        <div id="myTabContent" class="tab-content">
            @include('partials.statistics.leaderboardsOfClub', [
                'infos' => $infos,
                'showClubName' => true,
                'name' => 'all',
            ])
            @foreach ($clubInfos as $title => $clubInfo)
                @include('partials.statistics.leaderboardsOfClub', [
                    'infos' => $clubInfo,
                    'showClubName' => false,
                    'name' => str_replace(' ', '-', strtolower($title)),
                ])
            @endforeach
        </div>
    </div>
</div>
