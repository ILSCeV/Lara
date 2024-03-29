@php
    /** @var \Lara\ClubEvent $clubEvent */
    $isAllowedToSee = (\Auth::hasUser() && \Auth::user()->hasPermissionsInSection($clubEvent->section, \Lara\utilities\RoleUtility::PRIVILEGE_CL)) || Lara\Person::isCurrent($created_by);
    $isUnBlocked = is_null($clubEvent->unlock_date) || \Carbon\Carbon::now()->greaterThanOrEqualTo($clubEvent->unlock_date) || $isAllowedToSee;
@endphp
@extends('layouts.master')
@section('title')
    {{ $clubEvent->evnt_title }}
@stop
@section('moreScripts')
    <script src="{{ asset(WebpackBuiltFiles::$assets['autocomplete.js']) }}"></script>
@endsection
@section('content')
    <div class="panelEventView">
        <div class="row g-2">
            <div class="col-12 col-md-6">
                <div class="card">
                    @php
                        $clubEventClass = \Lara\utilities\ViewUtility::getEventPaletteClass($clubEvent);
                        $commonHeader = 'card-header ' . $clubEventClass;
                    @endphp
                    {{-- Set card color --}}
                    <div class="{{$commonHeader}}">
                        <div class="card-title">
                            <h4 @class(['event-cancelled' => $clubEvent->canceled == 1]) >@include('partials.event-marker')&nbsp;{{ $clubEvent->evnt_title }}</h4>
                            <h5>{{ $clubEvent->evnt_subtitle }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                    <table class="table table-hover table-sm">
                        <tr>
                            <td>
                                <i>{{ __('mainLang.type') }}:</i>
                            </td>
                            <td>
                                {{ \Lara\Utilities::getEventTypeTranslation($clubEvent->evnt_type) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i>{{ __('mainLang.begin') }}:</i>
                            </td>
                            <td>
                                {{ strftime('%a, %d. %b %Y', strtotime($clubEvent->evnt_date_start)) }} um
                                {{ date('H:i', strtotime($clubEvent->evnt_time_start)) }}
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <i>{{ __('mainLang.end') }}:</i>
                            </td>
                            <td>
                                {{ strftime('%a, %d. %b %Y', strtotime($clubEvent->evnt_date_end)) }} um
                                {{ date('H:i', strtotime($clubEvent->evnt_time_end)) }}
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <i>{{ __('mainLang.DV-Time') }}:</i>
                            </td>
                            <td>
                                {{ date('H:i', strtotime($clubEvent->getSchedule->schdl_time_preparation_start)) }}
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <i>{{ __('mainLang.club') }}:</i>
                            </td>
                            <td>
                                {{ $clubEvent->section->title }}
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <i>{{ __('mainLang.willShowFor') }}:</i>
                            </td>
                            <td>{{ implode(', ', $clubEvent->showToSectionNames()) }}</td>
                        </tr>


                        {{-- Internal event metadata --}}
                        @auth
                            @if (isset($clubEvent->facebook_done))
                                <tr>
                                    <td style="width:33%">
                                        <i>{{ __('mainLang.faceDone') }}?</i>
                                    </td>
                                    <td>
                                        @if ($clubEvent->facebook_done == 1)
                                            <i class="text-success" aria-hidden="true">{{ __('mainLang.yes') }}</i>
                                        @else
                                            <i class="text-danger" aria-hidden="true">{{ __('mainLang.no') }}</i>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            @if ($clubEvent->event_url != null && $clubEvent->event_url != '')
                                <tr>
                                    <td style="width:33%">
                                        <i>{{ __('mainLang.eventUrl') }}:</i>
                                    </td>
                                    <td style="max-width:100px">
                                        <a target="_blank" class="d-inline-block text-truncate" style="max-width: 45%;"
                                            href="{{ $clubEvent->event_url }}">
                                            {{ $clubEvent->event_url }}
                                        </a>
                                    </td>
                                </tr>
                            @endif
                            @if (isset($clubEvent->price_tickets_normal))
                                <tr>
                                    <td style="width:33%">
                                        <i>{{ __('mainLang.priceTickets') }}:</i>
                                    </td>
                                    <td>
                                        {{ $clubEvent->price_tickets_normal !== null ? $clubEvent->price_tickets_normal : '--' }}
                                        €
                                        /
                                        {{ $clubEvent->price_tickets_external !== null ? $clubEvent->price_tickets_external : '--' }}
                                        €
                                        ({{ __('mainLang.studentExtern') }})
                                    </td>
                                </tr>
                            @endif
                            @if (isset($clubEvent->price_normal))
                                <tr>
                                    <td style="width:33%">
                                        <i>{{ __('mainLang.price') }}:</i>
                                    </td>
                                    <td>
                                        {{ $clubEvent->price_normal !== null ? $clubEvent->price_normal : '--' }} €
                                        /
                                        {{ $clubEvent->price_external !== null ? $clubEvent->price_external : '--' }} €
                                        ({{ __('mainLang.studentExtern') }})
                                    </td>
                                </tr>
                            @endif

                            {{--

    							Disabling iCal until fully functional.


    							<tr>
    								<td width="20%" class="ps-3">
    									<i>{{ __('mainLang.iCal') }}:</i>
    								</td>
    								<td>
    									@if ($clubEvent->evnt_is_published === '1')
    										<i class="fa fa-check-square-o" aria-hidden="true"></i>
    										&nbsp;&nbsp;{{__('mainLang.eventIsPublished')}}
    									@else
    										<i class="fa fa-square-o" aria-hidden="true"></i>
    										&nbsp;&nbsp;{{__('mainLang.eventIsUnpublished')}}
    									@endif
    								</td>
    							</tr>

    							--}}
                        </table>
                    @else
                        </table>
                    @endauth
                    </div>

                    {{-- CRUD --}}
                    @include('partials/events/editOptions', ['event' => $clubEvent])
                </div>
            </div>

            <div class="col-12 col-md-6 p-0">
                @if ($clubEvent->evnt_public_info != '')
                    <div class="card mx-2">
                        <div class="card-body more-info">
                            <h5 class="card-title">{{ __('mainLang.additionalInfo') }}:</h5>
                            {!! $clubEvent->publicInfoMd() !!}
                        </div>
                        <button type="button" class="moreless-more-info btn btn-primary btn-margin"
                            data-dismiss="alert">{{ __('mainLang.showMore') }}</button>
                        <button type="button" class="moreless-less-info btn btn-primary btn-margin"
                            data-dismiss="alert">{{ __('mainLang.showLess') }}</button>
                    </div>
                @endif

                @auth
                    @if ($clubEvent->evnt_private_details != '')
                        <br>
                        <div class="card mx-2 hidden-print">
                            <div class="card-body more-details">
                                <h5 class="card-title">{{ __('mainLang.moreDetails') }}:</h5>
                                {!! $clubEvent->privateDetailsMd() !!}
                            </div>
                            <button type="button" class="moreless-more-details btn btn-primary btn-margin"
                                data-dismiss="alert">{{ __('mainLang.showMore') }}</button>
                            <button type="button" class="moreless-less-details btn btn-primary btn-margin"
                                data-dismiss="alert">{{ __('mainLang.showLess') }}</button>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        @if(!$shifts->isEmpty())
        <div class="row my-3 hidden-print justify-content-start">
            <div class="col-md-5">
                <div class="btn-group" role="group" aria-label="Filters">
                    {{-- show time button Ger.: Zeiten einblenden --}}
                    <button class="btn btn-sm" type="button" id="toggle-shift-time">
                        {{ __('mainLang.hideTimes') }}
                    </button>

                    {{-- hide taken shifts button Ger.: Vergebenen Diensten ausblenden --}}
                    <button class="btn btn-sm" type="button" id="toggle-taken-shifts">
                        {{ __('mainLang.hideTakenShifts') }}
                    </button>

                    {{-- show/hide all comment fields --}}
                    <button class="btn btn-sm" type="button" id="toggle-all-comments">
                        {{ __('mainLang.comments') }}
                    </button>
                </div>
            </div>
        </div>
        @endif

        <div class="row mb-3 justify-content-center">
            @if($shifts->isEmpty())
            <div class="alert alert-info col-md-9 mt-4" role="alert">
                {{__('mainLang.noShifts')}}
              </div>
            @else
            <div class="col-12 mx-sm-1 m-auto">
                <div class="card">
                    @if ($clubEvent->getSchedule->schdl_password != '')
                        @if (is_null($clubEvent->unlock_date) || \Carbon\Carbon::now()->greaterThanOrEqualTo($clubEvent->unlock_date))
                            <div class="card-header hidden-print">
                                {!! Form::password('password', [
                                    'required',
                                    'class' => 'col-md-4 col-sm-4 col-12',
                                    'id' => 'password' . $clubEvent->getSchedule->id,
                                    'placeholder' => __('mainLang.enterPasswordHere'),
                                ]) !!}
                            </div>
                        @endif
                    @endif
                    @if ($isUnBlocked)
                        <div class="card-body">
                        @include('partials.shifts.takeShiftTable', [
                            'shifts' => $shifts,
                            'hideComments' => false,
                            'commentsInSeparateLine' => false,
                        ])
                        </div>
                    @else
                        <div class="card col-12 mx-sm-1 m-auto text-center">
                            {{ __('mainLang.availableAt') }} {{ $clubEvent->unlock_date->isoFormat('DD.MM.YYYY hh:mm') }}
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    @auth
        {{-- REVISIONS --}}
        <div class="row mx-md-5 mx-sm-1 m-0">
            <a id="show-hide-history" class="text-body-secondary hidden-print" href="#">
                {{ __('mainLang.listChanges') }} &nbsp;&nbsp;<i class="fa fa-caret-right" id="arrow-icon"></i>
            </a>
        </div>

        <div class="row mx-md-5 mx-sm-1 my-3">
            <div class="card col-12 p-0 m-0 hide" id="change-history">
                <table class="table table-hover">
                    <thead>
                        <th scope="col">
                            {{ __('mainLang.work') }}
                        </th>
                        <th scope="col">
                            {{ __('mainLang.whatChanged') }}
                        </th>
                        <th scope="col">
                            {{ __('mainLang.oldEntry') }}
                        </th>
                        <th scope="col">
                            {{ __('mainLang.newEntry') }}
                        </th>
                        <th scope="col">
                            {{ __('mainLang.whoBlame') }}
                        </th>
                        <th scope="col">
                            {{ __('mainLang.whenWasIt') }}
                        </th>
                    </thead>
                    <tbody>
                        @foreach ($revisions as $revision)
                            <tr>
                                <td scope="row">
                                    {{ $revision['job type'] }}
                                </td>
                                <td>
                                    {{ __($revision['action']) }}
                                </td>
                                <td>
                                    {{ $revision['old value'] }}
                                </td>
                                <td>
                                    {{ $revision['new value'] }}
                                </td>
                                <td>
                                    {{ $revision['user name'] }}
                                </td>
                                <td>
                                    {{ $revision['timestamp'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endauth

@stop
