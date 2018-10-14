<div class="card p-0">
    <div class="card-header">
        <h4 class="card-title padding-all-sides-16px">
            {{ trans('mainLang.infoFor') }}
        </h4>
    </div>

   <ul class="nav nav-tabs">
        @foreach($clubInfos->keys() as $title)
            <li class="{{Lara\Section::current()->title == $title? 'active': ''}} statisticClubPicker nav-item">
                <a aria-expanded="{{Lara\Section::current()->title == $title? 'true' : 'false'}}"
                   href="#{{ str_replace(' ', '-', strtolower($title)) }}"
                   data-toggle="tab" class="nav-link">
                    {{$title}}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div class="card card-body p-0">
    <div id="memberStatisticsTabs" class="tab-content">
        @foreach($clubInfos as $title => $clubInfo)
            <div class="tab-pane fade in {{ Lara\Section::current()->title === $title ? 'active' : '' }}"
                 id="{{ str_replace(' ', '-', strtolower($title)) }}">
                <table class="table table-hover" >
                    <thead>
                        <tr>
                            <td data-sort="name" class="col-md-2 col-sm-3 col-xs-4">
                                {{trans('mainLang.name')}} <i class="fas fa-sort-down fa-pull-right"></i>
                            </td>
                            <td data-sort="shifts" class="col-md-2 col-sm-3 col-xs-3">
                                {{trans('mainLang.totalShifts')}}<i class="fas fa-sort fa-pull-right"></i>
                            </td>
                            <td data-sort="shifts" class="col-md-8 col-sm-6 col-xs-5">
                                &nbsp;
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clubInfo as $info)
                            @php
                                $user = $info->user->user;
                            @endphp
                            <tr class="{{Auth::user()->id === $user->id? 'my-shift' : ''}}">
                                <td>
                                    @include('partials.personStatusMarker', ['status' => $user->status, 'section' => $user->section])
                                    <a href="#" onclick="chosenPerson = '{{$user->name}}'" name="show-stats-person{{$info->user->id}}" id="{{$info->user->id}}" data-toggle="tooltip" data-placement="top" title="{{ $user->fullName() }}">
                                            {{$user->name}}
                                    </a>
                                </td>
                                <td>
                                    @include('partials.statistics.amountOfShiftsDisplay')
                                </td>
                                <td>
                                    @include('partials.statistics.graphicShifts')
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</div>
