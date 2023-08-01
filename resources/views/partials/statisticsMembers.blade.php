<div class="card">
    <div class="card-header">
        <h4 class="card-title">
            {{ __('mainLang.infoFor') }}
        </h4>
        <ul class="nav nav-tabs card-header-tabs">
            @foreach ($clubInfos->sortKeys() as $title => $info)
                <li class="statisticClubPicker nav-item">
                    <a data-section="{{$title}}" 
                    href="#{{ str_replace(' ', '-', mb_strtolower($title)) }}" 
                    data-bs-toggle="tab" 
                    class="nav-link">
                        {{ $title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="card-body">
        <div id="memberStatisticsTabs" class="tab-content">
            @php
                /**
                 * @var \Illuminate\Support\Collection|\Lara\StatisticsInformation $clubInfo
                 */
            @endphp
            @foreach ($clubInfos->sortKeys() as $title => $clubInfo)
                <div class="tab-pane fade"
                    id="{{ str_replace(' ', '-', mb_strtolower($title)) }}">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th data-sort="name" data-sortable="true" class="col-3 col-lg-4">
                                    {{ __('mainLang.name') }} <i class="fa-solid  fa-sort-down fa-pull-right"></i>
                                </th>
                                <th data-sort="shifts" data-sortable="true" class="col-1 col-lg-1">
                                    {{ __('mainLang.totalShifts') }} <i class="fa-solid  fa-sort fa-pull-right"></i>
                                </th>
                                <th data-sort="flood" data-sortable="true" class="col-1 col-lg-1">
                                    {{ __('mainLang.flooding') }} <i class="fa-solid  fa-sort fa-pull-right"></i>
                                </th>
                                <th data-sort="shifts" class="col">
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clubInfo as $info)
                                @php
                                    $user = $info->user()->first();
                                @endphp
                                <tr class="{{ Auth::user()->id === $user->id ? 'my-shift' : '' }}">
                                    <td>
                                        @include('partials.personStatusMarker', [
                                            'status' => $user->status,
                                            'section' => $user->section,
                                        ])
                                        <a href="#" onclick="chosenPerson = '{{ $user->name }}'"
                                            name="show-stats-person{{ $info->person->id }}"
                                            id="{{ $info->person->id }}" data-bs-toggle="modal" data-bs-placement="top"
                                            title="{{ $user->fullName() }}">
                                            {{ $user->name }}
                                        </a>
                                    </td>
                                    <td>
                                        @include('partials.statistics.amountOfShiftsDisplay')
                                    </td>
                                    <td>
                                        <span data-bs-toggle="tooltip" title="{{ __('mainLang.floodShifts') }}">
                                            {{ $info->flood_shift }}
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
</div>
