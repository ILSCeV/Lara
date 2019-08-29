<div class="card p-0">
    <div class="card-header">
        <h4 class="card-title p-3">
            {{ trans('mainLang.infoFor') }}
        </h4>
    </div>

   <ul class="nav nav-tabs">
       @foreach($clubInfos->sortKeys() as $title => $info)
            <li class="{{Lara\Section::current()->title === $title? 'active': ''}} statisticClubPicker nav-item">
                <a aria-expanded="{{Lara\Section::current()->title == $title? 'true' : 'false'}}"
                   href="#{{ str_replace(' ', '-', mb_strtolower($title)) }}"
                   data-toggle="tab" class="nav-link">
                    {{$title}}
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div class="card card-body p-0">
    <div id="memberStatisticsTabs" class="tab-content">
        @php
        /**
          * @var \Illuminate\Support\Collection|\Lara\StatisticsInformation $clubInfo
          */
        @endphp
        @foreach($clubInfos->sortKeys()  as $title => $clubInfo)
            <div class="tab-pane fade in {{ Lara\Section::current()->title === $title ? 'active' : '' }}"
                 id="{{ str_replace(' ', '-', mb_strtolower($title)) }}">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th data-sort="name" data-sortable="true">
                                {{trans('mainLang.name')}} <i class="fas fa-sort-down fa-pull-right"></i>
                            </th>
                            <th data-sort="shifts" data-sortable="true">
                                {{trans('mainLang.totalShifts')}} <i class="fas fa-sort fa-pull-right"></i>
                            </th>
                            <th data-sort="flood" data-sortable="true">
                                {{ trans('mainLang.flooding') }} <i class="fas fa-sort fa-pull-right"></i>
                            </th>
                            <th data-sort="shifts" class="col">
                                &nbsp;
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clubInfo as $info)
                            @php
                                $user = $info->user()->first();
                            @endphp
                            <tr class="{{Auth::user()->id === $user->id? 'my-shift' : ''}}">
                                <td>
                                    @include('partials.personStatusMarker', ['status' => $user->status, 'section' => $user->section])
                                    <a href="#" onclick="chosenPerson = '{{$user->name}}'" name="show-stats-person{{$info->person->id}}" id="{{$info->person->id}}" data-toggle="modal" data-placement="top" title="{{ $user->fullName() }}">
                                            {{$user->name}}
                                    </a>
                                </td>
                                <td>
                                    @include('partials.statistics.amountOfShiftsDisplay')
                                </td>
                                <td>
                                    <span data-toggle="tooltip" title="{{trans('mainLang.floodShifts')}}">
                                        {{$info->flood_shift}}
                                    </span>
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
