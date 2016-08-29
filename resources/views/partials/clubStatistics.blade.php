<div>
    <ul class="nav nav-tabs">
        @foreach($clubInfos->keys() as $title)
            <li class=><a aria-expanded="{{Session::get('userClub') == $title? 'true' : 'false'}}" href="#{{$title}}" data-toggle="tab">{{$title}}</a></li>
        @endforeach
    </ul>
    <div id="myTabContent" class="tab-content">
        @foreach($clubInfos as $title => $clubInfo)
            <div class="tab-pane fade in {{Session::get('userClub') == $title? 'active': ''}}" id="{{$title}}">
                <h3> Info für {{$title}}</h3>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <td>{{trans('mainLang.name')}}</td>
                        <td>{{trans('mainLang.totalShifts')}}</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clubInfo as $info)
                        <tr>
                            <td>
                                {{$info->user->prsn_name }}
                            </td>
                            <td>
                                {{$info->totalShifts}}
                            </td>
                            <td width="50%">
                                <div class="progress">
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