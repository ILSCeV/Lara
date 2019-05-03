@extends('layouts.master')
@php
    /**
    *   @var \Lara\User $user
    *   @var \Illuminate\Support\Collection|\Lara\Shift $shifts
    */
@endphp
@section('title')
    {{ trans('mainLang.userPersonalPage') }} - {{ $user->name }} ({{ $user->section->title }})
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card ">
            <div class="card-header text-white bg-info">
                <h4 class="card-title ">
                    {{ trans('mainLang.userPersonalPage') }}:
                    {{ $user->name }} ({{ $user->section->title }})
                </h4>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#shifts" class="nav-link active" data-toggle="tab">{{trans('mainLang.upcomingShifts')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#settings" data-toggle="tab">{{trans('mainLang.settings')}}</a>
                    </li>
                </ul>
                <div id="tabContent" class="tab-content">
                    <div class="tab-pane fade active in show" id="shifts">
                        <table class="table table-hover col-md-12">
                            <caption class="text-center"></caption>
                            <thead>
                                <tr>
                                    <th class="text-center col-md-3">{{trans('mainLang.shift')}}</th>
                                    <th class="text-center col-md-3">{{trans('mainLang.event')}}</th>
                                    <th class="text-center col-md-3">{{trans('mainLang.date')}}</th>
                                    <th class="text-center col-md-3">{{trans('mainLang.begin')}}-{{trans('mainLang.end')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shifts as $shift )
                                    <tr>
                                        <td class="text-center">
                                            {{ $shift->type->title }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('event.show',$shift->schedule->event->id) }}">{{ $shift->schedule->event->evnt_title }}</a>
                                        </td>
                                        <td class="text-center">
                                            {{ (new DateTime($shift->schedule->event->evnt_date_start))->format('D, d.m.Y') }}
                                        </td>
                                        <td class="text-center">
                                            {{ (new DateTime($shift->start))->format('H:i') }} - {{ (new DateTime($shift->end))->format('H:i') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="settings">
                        <div class="p-3">
                            {{Form::open(['route' => 'user.updatePersonalSettings'])}}
                                <label for="privateClubName">
                                    <strong>{{ trans('mainLang.privateClubName') }}</strong>
                                </label>
                                <br class="d-block d-md-none d-lg-none">
                                <div class="form-check-inline">
                                    <label class="form-check">
                                        {!! Form::radio( 'is_name_private','null', is_null($user->is_name_private )) !!}
                                        {{ trans('mainLang.privateClubNameNull') }}
                                    </label>
                                </div>
                                <br class="d-block d-md-none d-lg-none">
                                <div class="form-check-inline">
                                    <label class="form-check">
                                        {!! Form::radio( 'is_name_private','true', !is_null($user->is_name_private ) && $user->is_name_private == 1 ) !!}
                                        {{ trans('mainLang.privateClubNameYes') }}
                                    </label>
                                </div>
                                <br class="d-block d-md-none d-lg-none">
                                <div class="form-check-inline">
                                    <label class="form-check">
                                        {!! Form::radio( 'is_name_private','false', !is_null($user->is_name_private ) && $user->is_name_private == 0) !!}
                                        {{ trans('mainLang.privateClubNameNo') }}
                                    </label>
                                </div>
                                <br>
                                <br>
                                <div class="btn-group btn-group-sm">
                                    <button type="reset"
                                            class="btn btn-sm btn-secondary">{{ trans('mainLang.reset') }}</button>
                                    <button type="submit"
                                            class="btn btn-sm btn-success">{{ trans('mainLang.update') }}</button>
                                </div>
                            {{Form::close()}}
                        </div>

                        <hr class="p-0 m-0">

                        <div class="p-3">
                            @auth
                                @noLdapUser
                                    <a href="{{route('password.change')}}">
                                        <i class="fa fa-key fa-rotate-90" aria-hidden="true"></i>
                                        <strong>{{ trans('auth.changePassword') }}</strong>
                                    </a>
                                @endnoLdapUser
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
