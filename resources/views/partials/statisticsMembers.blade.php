<div class="panel panel-heading no-padding">
    <h4 class="panel-title all-sides-padding-16">
        {{ trans('mainLang.infoFor') }}
    </h4>

   <ul class="nav nav-tabs">
        @foreach($clubInfos->keys() as $title)
            <li class="{{Session::get('userClub') == $title? 'active': ''}}">
                <a aria-expanded="{{Session::get('userClub') == $title? 'true' : 'false'}}" 
                   href="#{{$title}}" 
                   data-toggle="tab">
                    {{$title}}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div class="panel panel-body no-padding">
    <div id="leaderboardsTabs" class="tab-content">
        @foreach($clubInfos as $title => $clubInfo)
            <div class="tab-pane fade in {{ Session::get('userClub') === $title ? 'active' : '' }}" id="{{$title}}">
                <table class="table table-hover" >
                    <thead>
                        <tr>
                            <td data-sort="name" class="col-md-2 col-sm-3 col-xs-4">
                                {{trans('mainLang.name')}} <i class="fa fa-sort-desc fa-pull-right"></i>
                            </td>
                            <td data-sort="shifts" class="col-md-2 col-sm-3 col-xs-3">
                                {{trans('mainLang.totalShifts')}}<i class="fa fa-sort fa-pull-right"></i>
                            </td>
                            <td data-sort="shifts" class="col-md-8 col-sm-6 col-xs-5">
                                &nbsp;
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clubInfo as $info)
                            <tr class="{{$info->user->isLoggedInUser() ? 'my-shift' : ''}}">
                                <td>
                                    @include('partials.personStatusMarker', ['person' => $info->user]){{$info->user->prsn_name }}
                                </td>
                                <td>
                                    {{$info->totalShifts}}
                                </td>
                                <td>
                                    <div class="progress centered">
                                        <div class="progress-bar" style="width: {{$info->shiftsPercent}}%;"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</div>
